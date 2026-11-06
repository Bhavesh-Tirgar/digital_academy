<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiDebuggerController extends Controller
{
    /**
     * Show Standalone Interactive AI Code Sandbox Lab.
     */
    public function index()
    {
        $challenges = [
            [
                'id' => 'binary_search',
                'title' => 'Binary Search (Logarithmic Speed)',
                'difficulty' => 'Medium',
                'language' => 'javascript',
                'description' => 'Implement an optimal search algorithm returning the index of target in sorted array, or -1 if not found.',
                'starter_code' => "function findTarget(arr, target) {\n    let left = 0, right = arr.length - 1;\n    while (left <= right) {\n        let mid = Math.floor((left + right) / 2);\n        if (arr[mid] === target) return mid;\n        if (arr[mid] < target) left = mid + 1;\n        else right = mid - 1;\n    }\n    return -1;\n}",
                'test_cases' => [
                    ['input' => '[[2, 5, 8, 12, 16], 12]', 'expected' => '3', 'label' => 'Standard element find'],
                    ['input' => '[[1, 3, 5], 4]', 'expected' => '-1', 'label' => 'Missing element in range'],
                    ['input' => '[[], 10]', 'expected' => '-1', 'label' => 'Empty array edge-case'],
                ]
            ],
            [
                'id' => 'two_sum',
                'title' => 'Two Sum (Linear Hash Map)',
                'difficulty' => 'Easy',
                'language' => 'javascript',
                'description' => 'Given an array of numbers, return the indices of two numbers that add up to target in O(n) time.',
                'starter_code' => "function twoSum(nums, target) {\n    const map = new Map();\n    for (let i = 0; i < nums.length; i++) {\n        const complement = target - nums[i];\n        if (map.has(complement)) {\n            return [map.get(complement), i];\n        }\n        map.set(nums[i], i);\n    }\n    return [];\n}",
                'test_cases' => [
                    ['input' => '[[2, 7, 11, 15], 9]', 'expected' => '[0, 1]', 'label' => 'Pair found at start'],
                    ['input' => '[[3, 2, 4], 6]', 'expected' => '[1, 2]', 'label' => 'Pair in middle'],
                    ['input' => '[[3, 3], 6]', 'expected' => '[0, 1]', 'label' => 'Duplicate matching numbers'],
                ]
            ],
            [
                'id' => 'reverse_string',
                'title' => 'In-Place String Reversal',
                'difficulty' => 'Easy',
                'language' => 'javascript',
                'description' => 'Reverse an array of characters in-place using two pointers with O(1) auxiliary memory.',
                'starter_code' => "function reverseString(s) {\n    let left = 0, right = s.length - 1;\n    while (left < right) {\n        let temp = s[left];\n        s[left] = s[right];\n        s[right] = temp;\n        left++;\n        right--;\n    }\n    return s;\n}",
                'test_cases' => [
                    ['input' => '[["h","e","l","l","o"]]', 'expected' => '["o","l","l","e","h"]', 'label' => 'Odd length string'],
                    ['input' => '[["H","a","n","n","a","h"]]', 'expected' => '["h","a","n","n","a","H"]', 'label' => 'Even length palindrome'],
                ]
            ]
        ];

        return view('frontend.sandbox', compact('challenges'));
    }

    /**
     * AI Code Debugger & Socratic Tutor API Endpoint.
     */
    public function debug(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'language' => 'nullable|string',
            'task_context' => 'nullable|string',
            'mode' => 'nullable|string', // 'debug', 'hint', 'complexity', 'review'
            'runtime_error' => 'nullable|string',
        ]);

        $code = $request->input('code');
        $language = strtolower($request->input('language', 'javascript'));
        $context = $request->input('task_context', '');
        $mode = $request->input('mode', 'debug');
        $runtimeError = $request->input('runtime_error', '');

        $analysis = $this->analyzeCodeHeuristics($code, $language, $context, $runtimeError);

        return response()->json([
            'status' => 'success',
            'mode' => $mode,
            'analysis' => $analysis,
            'xp_awarded' => 25,
            'message' => 'Professor AI analysis generated successfully.',
        ]);
    }

    /**
     * Socratic AI Analysis & Big-O Complexity Engine.
     */
    private function analyzeCodeHeuristics(string $code, string $language, string $context, string $runtimeError): array
    {
        $hasBugs = false;
        $bugType = 'None Detected';
        $suspectLine = null;
        $socraticHint = '';
        $professorNote = '';
        $timeComplexity = 'O(n)';
        $spaceComplexity = 'O(1)';
        $efficiencyScore = '95%';

        // 1. Check for Runtime/Syntax Error
        if (!empty($runtimeError)) {
            $hasBugs = true;
            $bugType = 'Syntax / Execution Exception';
            $socraticHint = "Your code encountered an execution error: '{$runtimeError}'. Check your brackets, variable scoping, and property lookups.";
            $professorNote = "Don't worry—syntax and runtime exceptions are a normal part of engineering! Carefully inspect the line mentioned in the traceback.";
            return [
                'has_bugs' => true,
                'bug_type' => $bugType,
                'suspect_line' => 1,
                'socratic_hint' => $socraticHint,
                'time_complexity' => 'Undefined (Halted)',
                'space_complexity' => 'Undefined',
                'efficiency_score' => '0%',
                'professor_note' => $professorNote,
                'recommendation' => 'Fix the syntax exception before running algorithmic verification.'
            ];
        }

        // 2. Binary Search Heuristics
        if (stripos($context, 'binary') !== false || stripos($code, 'findTarget') !== false) {
            $timeComplexity = 'O(log n)';
            $spaceComplexity = 'O(1)';

            if (preg_match('/while\s*\(\s*left\s*<\s*right\s*\)/', $code)) {
                $hasBugs = true;
                $bugType = 'Boundary Off-By-One';
                $suspectLine = 3;
                $socraticHint = "Look closely at your while condition: `left < right`. What happens when the array contains only a single element (e.g. `[5]` with target `5`)? Should `left === right` be evaluated?";
                $professorNote = "In binary search, if `right = arr.length - 1`, the search interval is inclusive `[left, right]`. Using `<` instead of `<=` causes the loop to exit early on 1-element arrays!";
                $efficiencyScore = '70%';
            } elseif (strpos($code, 'Math.floor') === false && strpos($code, '>> 1') === false && strpos($code, 'parseInt') === false && strpos($code, '//') === false) {
                $hasBugs = true;
                $bugType = 'Floating Point Index Error';
                $suspectLine = 4;
                $socraticHint = "JavaScript numbers are 64-bit IEEE 754 floats. What is `(0 + 1) / 2`? Is `0.5` a valid array index?";
                $professorNote = "Always wrap division in `Math.floor((left + right) / 2)` or use the bitwise right shift `((left + right) >> 1)` to maintain integer array indexing.";
                $efficiencyScore = '65%';
            } elseif (preg_match('/left\s*=\s*mid(?!\s*\+)/', $code) || preg_match('/right\s*=\s*mid(?!\s*\-)/', $code)) {
                $hasBugs = true;
                $bugType = 'Infinite Loop Hazard';
                $suspectLine = 6;
                $socraticHint = "Notice how you update pointers: setting `left = mid` or `right = mid` without `+ 1` or `- 1` can cause the interval size to remain constant when `right - left === 1`.";
                $professorNote = "Since `arr[mid]` has already been checked and confirmed not to equal the target, exclude it by advancing to `mid + 1` or `mid - 1`.";
                $efficiencyScore = '60%';
            } elseif (strpos($code, 'return -1') === false && strpos($code, 'return null') === false) {
                $hasBugs = true;
                $bugType = 'Missing Not-Found Fallback';
                $suspectLine = 9;
                $socraticHint = "If the target is not in the array and the loop terminates, what value should the function return according to the specification?";
                $professorNote = "Add a fallback `return -1;` after the while loop so caller contracts receive a predictable failure sentinel.";
                $efficiencyScore = '80%';
            } else {
                $hasBugs = false;
                $socraticHint = "Your binary search implementation is mathematically rigorous and handles all boundaries cleanly!";
                $professorNote = "Excellent engineering! Your two-pointer interval narrowing achieves textbook logarithmic O(log n) efficiency with zero auxiliary memory allocation.";
                $efficiencyScore = '100%';
            }
        } 
        // 3. Two Sum Heuristics
        elseif (stripos($context, 'two sum') !== false || stripos($code, 'twoSum') !== false) {
            if (preg_match('/for\s*\(.*for\s*\(/s', $code)) {
                $timeComplexity = 'O(n²)';
                $spaceComplexity = 'O(1)';
                $hasBugs = false;
                $bugType = 'Sub-optimal Complexity';
                $socraticHint = "Your nested loops work, but take O(n²) quadratic time. Can you trade a small amount of memory to find complements in O(1) time using a Hash Map or Object?";
                $professorNote = "In technical interviews, senior engineers look for the O(n) Hash Map approach: store each visited number's index as you iterate through the list.";
                $efficiencyScore = '75%';
            } elseif (strpos($code, 'Map') !== false || strpos($code, '{}') !== false) {
                $timeComplexity = 'O(n)';
                $spaceComplexity = 'O(n)';
                $hasBugs = false;
                $socraticHint = "Optimal linear scan with O(1) hash lookups detected. Your solution runs in optimal O(n) time!";
                $professorNote = "Textbook implementation! By storing complements in a hash map, you satisfy Google and Meta production standards.";
                $efficiencyScore = '100%';
            }
        }
        // 4. General Code Analysis
        else {
            // General complexity estimation
            $loopCount = substr_count($code, 'for (') + substr_count($code, 'while (') + substr_count($code, 'for(') + substr_count($code, 'while(');
            if ($loopCount >= 2) {
                $timeComplexity = 'O(n²)';
                $efficiencyScore = '75%';
            } elseif ($loopCount === 1) {
                $timeComplexity = 'O(n)';
                $efficiencyScore = '90%';
            } else {
                $timeComplexity = 'O(1)';
                $efficiencyScore = '98%';
            }

            if (strpos($code, 'new Array') !== false || strpos($code, '[]') !== false || strpos($code, 'new Map') !== false) {
                $spaceComplexity = 'O(n)';
            } else {
                $spaceComplexity = 'O(1)';
            }

            $socraticHint = "Your algorithm appears structured and clean. Ensure edge cases like null inputs, empty lists, and boundary values are tested.";
            $professorNote = "Clean modular syntax. Always verify edge-case bounds before deploying to production cohorts.";
        }

        return [
            'has_bugs' => $hasBugs,
            'bug_type' => $bugType,
            'suspect_line' => $suspectLine,
            'socratic_hint' => $socraticHint,
            'time_complexity' => $timeComplexity,
            'space_complexity' => $spaceComplexity,
            'efficiency_score' => $efficiencyScore,
            'professor_note' => $professorNote,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
