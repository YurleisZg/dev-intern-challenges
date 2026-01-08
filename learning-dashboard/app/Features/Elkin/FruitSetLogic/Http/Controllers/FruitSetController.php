<?php

namespace App\Features\Elkin\FruitSetLogic\Http\Controllers;

use Illuminate\Http\Request;

class FruitSetController
{
    private const FRUITS = ["Apple", "Banana", "Cherry", "Lemon", "Grape"];

    public function index(Request $request)
    {
        // Initialize baskets in session
        if (!$request->session()->has('fruit_basket_A')) {
            $request->session()->put('fruit_basket_A', []);
        }
        if (!$request->session()->has('fruit_basket_B')) {
            $request->session()->put('fruit_basket_B', []);
        }

        $basketA = $request->session()->get('fruit_basket_A', []);
        $basketB = $request->session()->get('fruit_basket_B', []);

        return view('fruit-set-logic::index', [
            'basketA' => $basketA,
            'basketB' => $basketB,
            'fruits' => self::FRUITS,
        ]);
    }

    public function addFruit(Request $request)
    {
        $fruit = $request->query('fruit');
        $basket = $request->query('basket');

        // Validate inputs
        if (in_array($fruit, self::FRUITS, true) && in_array($basket, ['A', 'B'], true)) {
            $sessionKey = 'fruit_basket_' . $basket;
            $current = $request->session()->get($sessionKey, []);
            $current[] = $fruit;
            $request->session()->put($sessionKey, array_values($current));
        }

        return redirect()->route('elkin.challenges.fruit-set-logic.index');
    }

    public function reset(Request $request)
    {
        $basket = $request->query('basket');

        if ($basket === 'all') {
            $request->session()->put('fruit_basket_A', []);
            $request->session()->put('fruit_basket_B', []);
        } elseif (in_array($basket, ['A', 'B'], true)) {
            $request->session()->put('fruit_basket_' . $basket, []);
        }

        return redirect()->route('elkin.challenges.fruit-set-logic.index');
    }

    public function performOperation(Request $request)
    {
        $operation = $request->query('op');
        $basketA = $request->session()->get('fruit_basket_A', []);
        $basketB = $request->session()->get('fruit_basket_B', []);

        $result = null;
        $explanation = "";

        switch ($operation) {
            case 'union':
                $result = array_values(array_unique(array_merge($basketA, $basketB)));
                $explanation = "A ∪ B = All fruits in A or B";
                break;

            case 'intersect':
                $result = array_values(array_unique(array_intersect($basketA, $basketB)));
                $explanation = "A ∩ B = Fruits present in both";
                break;

            case 'diffAB':
                $result = array_values(array_unique(array_diff($basketA, $basketB)));
                $explanation = "A - B = Fruits in A that are NOT in B";
                break;

            case 'diffBA':
                $result = array_values(array_unique(array_diff($basketB, $basketA)));
                $explanation = "B - A = Fruits in B that are NOT in A";
                break;

            case 'xor':
                $result = array_values(array_unique(array_merge(
                    array_diff($basketA, $basketB),
                    array_diff($basketB, $basketA)
                )));
                $explanation = "(A XOR B) = Unique fruits in each basket";
                break;

            case 'subset':
                $isSubset = empty(array_diff($basketA, $basketB));
                $result = $isSubset ? "Yes" : "No";
                $explanation = "A ⊆ B = Are all elements of A inside B?";
                break;

            case 'jaccard':
                $intersection = array_intersect($basketA, $basketB);
                $union = array_unique(array_merge($basketA, $basketB));
                $ratio = count($union) > 0 ? (count($intersection) / count($union)) * 100 : 0;
                $result = round($ratio, 2) . "%";
                $explanation = "Similarity = |A ∩ B| / |A ∪ B| * 100";
                break;
        }

        return view('fruit-set-logic::index', [
            'basketA' => $basketA,
            'basketB' => $basketB,
            'fruits' => self::FRUITS,
            'result' => $result,
            'explanation' => $explanation,
        ]);
    }
}
