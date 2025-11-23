# PHP Mastery Challenges

**Goal**: Master PHP's built-in tools.
**Focus**: Math Functions, Array Library, Session/URL Handling.

## 🏠 Requirement 0: The Evaluator's Dashboard
Create an `index.php` at the root of your folder (`Elkin/index.php` or `Yurleis/index.php`).
-   This page must list links to all 3 challenges below.
-   Each challenge page must have a "Back to Dashboard" button.

---

## 🧮 Challenge 1: The "PHP Accountant"
**Focus**: Math operations, Date/Time Logic, Arrays, Formatting.

**Scenario**: You are building a salary calculator with overtime support.
**Task**: Create a form that accepts a **Gross Monthly Salary** and allows adding **Multiple Overtime Shifts**.

**Logic**:
1.  **Base Salary**:
    -   Tax: < 1000 (0%), 1000-2000 (10%), > 2000 (20%).
    -   Health: Deduct 5%.
    -   Bonus: Random $100-$500.
2.  **Overtime (The Tricky Part)**:
    -   Allow the user to add **unlimited** rows of overtime (Date, Start Time, End Time).
    -   **Hourly Rate**: Calculate based on Monthly Salary / 160 (standard hours).
    -   **Upcharges**:
        -   **Sunday**: +50% of hourly rate.
        -   **Night Shift** (After 6:00 PM): +25% of hourly rate.
        -   *Note*: These stack! (e.g., Sunday Night = Base + 50% + 25%).
3.  **Totals**: Show the breakdown of Base Net Salary + Total Overtime Pay.

**Output**:
-   A clean summary table showing:
    -   Base Salary Breakdown (Tax, Health, Bonus).
    -   Overtime List (Date, Hours, Rate, Extra Charges, Total for that shift).
    -   **Grand Total Net Salary**.
-   **Formatting**: Currency format (`$1,250.00`) and round down totals.

---

## 📊 Challenge 2: The "Fruit Set" Logic
**Focus**: Array Set Operations (`array_intersect`, `array_diff`, `array_unique`, `array_merge`).

**Scenario**: You have two empty baskets (Basket A and Basket B).
**Task**: Create a visual interface to fill these baskets and analyze their relationship.

**Logic**:
1.  **State Management**:
    -   Start with **Empty Arrays** for Basket A and Basket B.
    -   **Fruit Buttons**: Display a list of available fruits (Apple, Banana, Cherry, Lemon, Grape) as buttons below *each* basket.
    -   Clicking a button adds that fruit to the specific basket (e.g., `?add=apple&basket=A`).
2.  **Set Operations (The Complex Part)**:
    -   Provide buttons for these operations. Display the result and a math explanation:
        -   **Union (A ∪ B)**: Everything in both.
        -   **Intersection (A ∩ B)**: Only what they share.
        -   **Difference (A - B)**: In A, but NOT in B.
        -   **Difference (B - A)**: In B, but NOT in A.
        -   **Symmetric Difference (A XOR B)**: Unique to each (Everything minus Intersection).
        -   **Is Subset? (A ⊆ B)**: Return "Yes" if all fruits in A are also in B.
        -   **Similarity % (Jaccard Index)**: Calculate `(Count(Intersection) / Count(Union)) * 100`.

**Output**:
-   Two distinct columns for A and B with their own "Add" buttons.
-   A control panel for the operations.
-   A clear Result Box showing the output array or value.

---

## 🔐 Challenge 3: The "Toggle" Time-Attack
**Focus**: Complex Session State, Timers, Multi-Step Logic.

**Scenario**: A high-pressure pattern matching game.
**Task**: Build a multi-stage game using `$_SESSION` to track state, score, and time.

**Stage 1: The Setup**
-   **UI**: Show 5 rows. Each row has 5 Checkboxes (Toggles), all initially **OFF**.
-   **Timer**: 20 Seconds.
-   **Goal**: The user must turn **ON** at least one toggle in *each* of the 5 rows.
-   **Validation**: When time is up (or user submits), check if *any* row is completely OFF.
    -   If yes: **Game Over**. Session destroyed. Redirect to Start.
    -   If no: Save the state of all 5 rows to `$_SESSION` and proceed to Stage 2.

**Stage 2: The Matching Game (Levels 1-5)**
-   **Flow**: Iterate through the 5 rows defined in Stage 1.
-   **UI**:
    -   **HUD**: Display "Current Level: X / 5" and "Strikes: X / 3".
    -   **Target (Top)**: Show the row from Stage 1 (Read-only).
    -   **Input (Bottom)**: Show 5 new toggles (All OFF).
-   **Timer**: Randomly chosen between 10s and 15s for each level.
-   **Goal**: User must make the Input toggles match the Target toggles exactly before time runs out.
-   **Win Condition**:
    -   If match is correct: Advance to next level.
    -   If all 5 levels complete: **Victory Screen**.
-   **Lose Condition**:
    -   If match is incorrect (or time runs out):
        -   Add a "Strike".
        -   Send user back to the **Previous Level** (e.g., if on Lvl 3, go to Lvl 2).
    -   **Three Strikes Rule**: If the user accumulates 3 strikes total, **Game Over** (Reset to Start).
