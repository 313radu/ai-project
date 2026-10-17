<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function ask(Request $request)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['nullable', 'array', 'max:10'],
        ]);

        $message = $data['message'];

        $words = collect(preg_split('/\s+/', mb_strtolower($message)))
            ->filter(fn ($w) => mb_strlen($w) >= 3)
            ->take(6);

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($words) {
                foreach ($words as $w) {
                    $q->orWhere('name', 'ilike', "%{$w}%")
                      ->orWhere('brand', 'ilike', "%{$w}%")
                      ->orWhere('category', 'ilike', "%{$w}%")
                      ->orWhere('description', 'ilike', "%{$w}%");
                }
            })
            ->limit(8)
            ->get();

        if ($products->isEmpty()) {
            $products = Product::where('is_active', true)->latest()->limit(6)->get();
        }

        $catalog = $products->map(fn ($p) => [
            'id'          => $p->id,
            'name'        => $p->name,
            'brand'       => $p->brand,
            'price'       => $p->price,
            'old_price'   => $p->old_price,
            'currency'    => $p->currency,
            'category'    => $p->category,
            'merchant'    => $p->merchant,
            'description' => mb_substr((string) $p->description, 0, 300),
        ])->values()->all();

        $system = "You are a friendly AI shopping assistant for a Romanian deals and affiliate site. "
            . "Reply in the user's language. Chat naturally, but recommend ONLY products from the CATALOG below. "
            . "Never invent products, prices or links. If nothing fits, say so and suggest a better search. Be concise. "
            . "At the very end add one line exactly like: IDS: [1,2] with the ids of products you mention, or IDS: [].\n"
            . 'CATALOG: ' . json_encode($catalog, JSON_UNESCAPED_UNICODE);

        $contents = collect($data['history'] ?? [])->map(fn ($h) => [
            'role'  => ($h['role'] ?? 'user') === 'model' ? 'model' : 'user',
            'parts' => [['text' => (string) ($h['text'] ?? '')]],
        ])->all();
        $contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];

        $model = config('services.gemini.model', 'gemini-2.5-flash');

        try {
            $res = Http::withHeaders(['x-goog-api-key' => (string) config('services.gemini.key')])
                ->timeout(30)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'systemInstruction' => ['parts' => [['text' => $system]]],
                    'contents'          => $contents,
                ]);
        } catch (\Throwable $e) {
            Log::error('Gemini request failed: ' . $e->getMessage());

            return response()->json(['reply' => 'Momentan nu pot răspunde, încearcă din nou.', 'products' => []], 502);
        }

        if ($res->failed()) {
            Log::error('Gemini error', ['status' => $res->status(), 'body' => $res->body()]);

            return response()->json(['reply' => 'Momentan nu pot răspunde, încearcă din nou.', 'products' => []], 502);
        }

        $text = (string) data_get($res->json(), 'candidates.0.content.parts.0.text', '');

        $ids = [];
        if (preg_match('/IDS:\s*\\[([^\\]]*)\]/', $text, $m)) {
            $ids  = array_filter(array_map('intval', explode(',', $m[1])));
            $text = trim(preg_replace('/IDS:\s*\\[[^\\]]*\]/', '', $text));
        }

        $shown = $products->whereIn('id', $ids)->values();

        return response()->json([
            'reply'    => $text !== '' ? $text : 'Nu am găsit un răspuns.',
            'products' => $shown->map(fn ($p) => [
                'name'     => $p->name,
                'price'    => $p->price,
                'currency' => $p->currency,
                'image'    => $p->image_url,
                'url'      => route('products.show', $p->slug),
            ])->all(),
        ]);
    }
}
