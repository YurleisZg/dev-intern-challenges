<?php

// Array of available fruits for the baskets
$fruits = [
    'apple',
    'banana',
    'orange',
    'grape',
    'mango',
    'kiwi',
    'pineapple',
    'peach',
    'pear',
    'plum',
    'watermelon',
    'cherry',
    'strawberry',
    'blueberry',
    'raspberry'
];

// Initialize baskets and result variables
$basketA = [];
$basketB = [];
$result = '';   
$result_explanation = '';

// Process POST requests
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get baskets from POST data
    $basketA = $_POST['basketA'];
    $basketB = $_POST['basketB'];

    // Ensure baskets are arrays (fallback to empty arrays if not)
    if(!is_array($basketA)) {
        $basketA = [];
    }
    if(!is_array($basketB)) {
        $basketB = [];
    }

    // Handle adding fruits to baskets
    if (isset($_POST['add'], $_POST['basket'])) {
        $fruit_to_add = $_POST['add'];
        $basket       = $_POST['basket'];

        // Validate fruit exists in available fruits list
        if (in_array($fruit_to_add, $fruits, true)) {
            // Add fruit to the specified basket
            if ($basket === 'A') {
                $basketA[] = $fruit_to_add;
            } elseif ($basket === 'B') {
                $basketB[] = $fruit_to_add;
            }
        }
    }

    // Handle clearing baskets
    if (isset($_POST['clear'])) {
        $basket_to_clear = $_POST['clear'];

        // Clear the specified basket
        if ($basket_to_clear === 'A') {
            $basketA = [];
        } elseif ($basket_to_clear === 'B') {
            $basketB = [];
        }
    }
}

// SET OPERATIONS FUNCTIONS

/**
 * Calculate union of two baskets (A ∪ B)
 * Returns all unique elements from both baskets
 */
function union ($basketA, $basketB) {
    return array_values(array_unique(array_merge($basketA, $basketB)));
}

/**
 * Calculate intersection of two baskets (A ∩ B)
 * Returns elements that exist in both baskets
 */
function intersection ($basketA, $basketB) {
    return array_values(array_intersect($basketA, $basketB));
}

/**
 * Calculate difference A - B
 * Returns elements that are in A but not in B
 */
function difference_basketA_basketB ($basketA, $basketB) {
    return array_values(array_diff($basketA, $basketB));
}

/**
 * Calculate difference B - A
 * Returns elements that are in B but not in A
 */
function difference_basketB_basketA ($basketA, $basketB) {
    return array_values(array_diff($basketB, $basketA));
}

/**
 * Calculate symmetric difference (A XOR B)
 * Returns elements that are in A or B, but not in both
 */
function symmetric_difference ($basketA, $basketB) {
    $diffA = difference_basketA_basketB($basketA, $basketB);
    $diffB = difference_basketB_basketA($basketA, $basketB);
    return array_values(array_merge($diffA, $diffB));
}

/**
 * Check if basket A is a subset of basket B (A ⊆ B)
 * Returns true if all elements of A exist in B
 */
function is_subset ($basketA, $basketB) {
    $array = difference_basketA_basketB($basketA, $basketB);
    return empty($array);
}

/**
 * Calculate Jaccard similarity coefficient
 * Returns percentage of similarity between two baskets
 * Formula: |A ∩ B| / |A ∪ B| * 100
 */
function Jaccard($basketA, $basketB) {
    $intersection = count(intersection($basketA, $basketB));
    $union = count(union($basketA, $basketB));
    if ($union == 0) {
        return 0;
    }
    return ($intersection / $union) * 100;
}

// Process the requested operation and generate results
switch($op = $_POST['op'] ) {
    case 'union':
        $res = union($basketA, $basketB);
        $result = 'Union (A ∪ B) = [' . implode(', ', $res) . ']';
        $result_explanation = 'We take all elements that are in A or B, without repetition.';
        break;
    case 'intersection':
        $res = intersection($basketA, $basketB);
        $result = 'Intersection (A ∩ B) = [' . implode(', ', $res) . ']';
        $result_explanation = 'Only elements that are simultaneously in A and B.';
        break;
    case 'diffAB':
        $res = difference_basketA_basketB($basketA, $basketB);
        $result = 'Difference (A - B) = [' . implode(', ', $res) . ']';
        $result_explanation = 'Elements that are in A but not in B.';
        break;
    case 'diffBA':
        $res = difference_basketB_basketA($basketA, $basketB);  
        $result = 'Difference (B - A) = [' . implode(', ', $res) . ']';
        $result_explanation = 'Elements that are in B but not in A.';
        break;  
    case 'symdiff':
        $res = symmetric_difference($basketA, $basketB);
        $result = 'Symmetric Difference (A XOR B) = [' . implode(', ', $res) . ']';
        $result_explanation = 'Elements that are in A or B, but not in both at the same time.';
        break;
    case 'subset':
        $is = is_subset($basketA, $basketB);
        if ($is) {
            $result = 'A ⊆ B (Yes, A is a subset of B)';
        } else {
            $result = 'A is NOT a subset of B';
        }
        $result_explanation = 'We check if all elements of A are contained within B.';
        break;
    case 'jaccard':
        $value = Jaccard($basketA, $basketB);
        $result = 'Similarity (Jaccard Index) = ' . number_format($value, 2) . '%';
        $result_explanation = 'J(A,B) = |A ∩ B| / |A ∪ B| * 100.';
        break;
    default:
        // No operation selected
        break;
}

?>