<?php

namespace App\Features\Yurleis\FruitSet\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FruitSetController
{
    private const SESSION_A = 'yurleis.fruit_set.basketA';
    private const SESSION_B = 'yurleis.fruit_set.basketB';

    private array $fruits = [
        'apple'  => 'Apple',
        'banana' => 'Banana',
        'cherry' => 'Cherry',
        'lemon'  => 'Lemon',
        'grape'  => 'Grape',
        'mango'  => 'Mango',
        'orange' => 'Orange',
        'peach'  => 'Peach',
        'pear'   => 'Pear',
        'plum'   => 'Plum',
        'kiwi'   => 'Kiwi',
        'strawberry' => 'Strawberry',
    ];

    public function index(Request $request)
    {
        // Only check if user is logged in with the yurleis guard
        if (!Auth::guard('yurleis')->check()) {
            return redirect()->route('yurleis.challenges.auth.login');
        }

        $a = $request->session()->get(self::SESSION_A, []);
        $b = $request->session()->get(self::SESSION_B, []);

        // Read query params
        $action = strtolower((string) $request->query('action', ''));
        $basket = strtoupper((string) $request->query('basket', ''));
        $fruit  = strtolower((string) $request->query('fruit', ''));
        $op     = strtolower((string) $request->query('op', ''));

        // Reset everything
        if ($request->boolean('reset')) {
            $a = [];
            $b = [];
        }

        // Clear one basket
        if ($action === 'clear' && $this->isValidBasket($basket)) {
            if ($basket === 'A') $a = [];
            if ($basket === 'B') $b = [];
        }

        // Add fruit
        if ($action === 'add' && $this->isValidBasket($basket) && $this->isValidFruit($fruit)) {
            if ($basket === 'A') $a[] = $fruit;
            if ($basket === 'B') $b[] = $fruit;
        }

        // Remove fruit
        if ($action === 'remove' && $this->isValidBasket($basket) && $this->isValidFruit($fruit)) {
            if ($basket === 'A') $a = $this->removeFruit($a, $fruit);
            if ($basket === 'B') $b = $this->removeFruit($b, $fruit);
        }

        // Normalize to sets (safe)
        $aSet = $this->toSet($a);
        $bSet = $this->toSet($b);

        // Save back to session
        $request->session()->put(self::SESSION_A, $aSet);
        $request->session()->put(self::SESSION_B, $bSet);

        // Compute operation
        $computed = $this->compute($op, $aSet, $bSet);

        return view('yurleis-fruit-set::index', [
            'fruits' => $this->fruits,
            'a' => $aSet,
            'b' => $bSet,
            'op' => $op,
            'result' => $computed['result'],
            'explanation' => $computed['explanation'],
            'counts' => $computed['counts'],
            'sets' => $computed['sets'],
        ]);
    }

    private function isValidBasket(string $basket): bool
    {
        return in_array($basket, ['A', 'B'], true);
    }

    private function isValidFruit(string $fruit): bool
    {
        return array_key_exists($fruit, $this->fruits);
    }

    private function removeFruit(array $basket, string $fruit): array
    {
        return array_values(array_filter($basket, fn ($f) => $f !== $fruit));
    }

    private function toSet(array $arr): array
    {
        $arr = array_values(array_filter($arr, fn ($v) => is_string($v)));


        $arr = array_map(fn ($v) => strtolower(trim($v)), $arr);

        $arr = array_values(array_filter($arr, fn ($v) => array_key_exists($v, $this->fruits)));

        $arr = array_values(array_unique($arr));
        sort($arr);

        return $arr;
    }

    private function compute(string $op, array $a, array $b): array
    {
        $union = $this->toSet(array_merge($a, $b));
        $intersection = $this->toSet(array_intersect($a, $b));
        $aMinusB = $this->toSet(array_diff($a, $b));
        $bMinusA = $this->toSet(array_diff($b, $a));
        $xor = $this->toSet(array_merge($aMinusB, $bMinusA));

        $counts = [
            'a' => count($a),
            'b' => count($b),
            'union' => count($union),
            'intersection' => count($intersection),
        ];

        $result = null;
        $explanation = "Choose an operation to see the result and the math.";

        switch ($op) {
            case 'union':
                $result = $union;
                $explanation = "Union (A ∪ B): everything that is in A or in B.\nA ∪ B = unique(merge(A, B))";
                break;

            case 'intersection':
                $result = $intersection;
                $explanation = "Intersection (A ∩ B): only fruits shared by both.\nA ∩ B = intersect(A, B)";
                break;

            case 'a-b':
                $result = $aMinusB;
                $explanation = "Difference (A − B): fruits in A but NOT in B.\nA − B = diff(A, B)";
                break;

            case 'b-a':
                $result = $bMinusA;
                $explanation = "Difference (B − A): fruits in B but NOT in A.\nB − A = diff(B, A)";
                break;

            case 'xor':
                $result = $xor;
                $explanation = "Symmetric Difference (XOR): fruits unique to each basket.\n(A − B) ∪ (B − A)";
                break;

            case 'subset':
                $result = empty(array_diff($a, $b)) ? 'Yes' : 'No';
                $explanation = "Subset (A ⊆ B): Yes if all fruits in A also exist in B.\nA ⊆ B ⇔ (A − B) is empty";
                break;

            case 'jaccard':
                $u = count($union);
                $i = count($intersection);
                $percent = $u === 0 ? 0 : round(($i / $u) * 100, 2);
                $result = $percent . '%';
                $explanation = "Jaccard Similarity: (|A ∩ B| / |A ∪ B|) × 100\nHere: ($i / $u) × 100 = $percent%";
                break;
        }

        return [
            'result' => $result,
            'explanation' => $explanation,
            'counts' => $counts,
            'sets' => [
                'union' => $union,
                'intersection' => $intersection,
                'aMinusB' => $aMinusB,
                'bMinusA' => $bMinusA,
                'xor' => $xor,
            ],
        ];
    }
}
