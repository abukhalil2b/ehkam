<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class QuestionGenerator
{
    /**
     * Generate multiple choice questions using Gemini AI
     * 
     * @param string $topic The topic for the questions
     * @param int $count Number of questions to generate (1-15)
     * @return array|null Array of questions or null on failure
     */
    public function generate($topic, $count = 5)
    {
        try {
            $prompt = "أنشئ {$count} سؤال اختيار من متعدد بالعربية حول: {$topic}

المتطلبات:
- كل سؤال له 4 خيارات بالضبط
- خيار واحد فقط صحيح
- أجب بمصفوفة JSON فقط

مثال:
[
    {\"question\": \"ما هي عاصمة السعودية؟\", \"options\": [\"الرياض\", \"جدة\", \"الدمام\", \"مكة\"], \"correct_answer\": \"الرياض\"}
]

أجب بمصفوفة JSON فقط بدون أي نص إضافي:";

            // Check if API key is configured
            $apiKey = config('gemini.api_key');
            if (empty($apiKey)) {
                Log::error('Gemini API key is not configured');
                throw new \Exception('مفتاح API غير مُعرّف. يرجى إضافة GEMINI_API_KEY في ملف .env');
            }

            // Candidate models to try, in order of preference
            $models = [
                'gemini-2.5-flash',
                'gemini-2.0-flash',
                'gemini-pro-latest',
                'gemini-flash-latest',
            ];

            // Allow override via config/env
            if ($configModel = config('gemini.model')) {
                array_unshift($models, $configModel);
            }

            $models = array_unique($models);
            $result = null;
            $lastException = null;

            foreach ($models as $model) {
                try {
                    Log::info("Attempting Gemini API with model: {$model}", [
                        'topic' => $topic,
                        'count' => $count
                    ]);

                    $result = Gemini::generativeModel(model: $model)->generateContent($prompt);

                    if ($result) {
                        Log::info("Success with model: {$model}");
                        break;
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed with model: {$model}", ['error' => $e->getMessage()]);
                    $lastException = $e;
                    continue;
                }
            }

            if (!$result && $lastException) {
                Log::error('All Gemini models failed', [
                    'last_error' => $lastException->getMessage()
                ]);
                throw new \Exception('فشل الاتصال بـ Gemini API مع جميع النماذج المتاحة: ' . $lastException->getMessage());
            }

            if (!$result) {
                Log::error('Gemini returned null result');
                throw new \Exception('لم يتم استلام رد من Gemini API');
            }

            // Get text from result - handle different response formats
            $text = null;

            // Log result structure for debugging
            Log::debug('Gemini result structure', [
                'result_type' => gettype($result),
                'result_class' => is_object($result) ? get_class($result) : null,
                'result_methods' => is_object($result) ? get_class_methods($result) : null,
                'is_string' => is_string($result),
                'is_array' => is_array($result),
            ]);

            // Try different methods to extract text based on library version
            if (is_string($result)) {
                $text = $result;
            } elseif (method_exists($result, 'text')) {
                $text = $result->text();
            } elseif (method_exists($result, 'getText')) {
                $text = $result->getText();
            } elseif (method_exists($result, '__toString')) {
                $text = (string) $result;
            } elseif (is_object($result) && isset($result->text)) {
                $text = $result->text;
            } elseif (is_array($result)) {
                // Try to find text in array structure
                if (isset($result['text'])) {
                    $text = $result['text'];
                } elseif (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    $text = $result['candidates'][0]['content']['parts'][0]['text'];
                } elseif (isset($result[0]) && is_string($result[0])) {
                    $text = $result[0];
                }
            } elseif (is_object($result)) {
                // Try to access candidates property
                if (property_exists($result, 'candidates') && !empty($result->candidates)) {
                    $candidate = is_array($result->candidates) ? $result->candidates[0] : $result->candidates[0] ?? null;
                    if ($candidate) {
                        if (is_object($candidate) && property_exists($candidate, 'content')) {
                            $content = $candidate->content;
                            if (is_object($content) && property_exists($content, 'parts') && !empty($content->parts)) {
                                $part = is_array($content->parts) ? $content->parts[0] : $content->parts[0] ?? null;
                                if ($part) {
                                    if (is_string($part)) {
                                        $text = $part;
                                    } elseif (is_object($part) && property_exists($part, 'text')) {
                                        $text = $part->text;
                                    }
                                }
                            }
                        } elseif (is_array($candidate) && isset($candidate['content']['parts'][0]['text'])) {
                            $text = $candidate['content']['parts'][0]['text'];
                        }
                    }
                }
            }

            if (empty($text)) {
                // Last resort: try to convert to string or JSON
                if (is_object($result) || is_array($result)) {
                    $text = json_encode($result, JSON_UNESCAPED_UNICODE);
                    Log::warning('Using JSON encoded result as text', ['json' => substr($text, 0, 500)]);
                }

                if (empty($text)) {
                    Log::error('Gemini returned empty text after all attempts', [
                        'topic' => $topic,
                        'count' => $count,
                        'result_type' => gettype($result),
                        'result_class' => is_object($result) ? get_class($result) : null,
                        'result_string' => is_string($result) ? substr($result, 0, 500) : null,
                        'result_json' => is_object($result) || is_array($result) ? json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : null
                    ]);
                    throw new \Exception('تم استلام رد فارغ من Gemini API. يرجى التحقق من مفتاح API والاتصال بالإنترنت.');
                }
            }

            Log::info('Gemini response received', [
                'topic' => $topic,
                'response_length' => strlen($text),
                'response_preview' => substr($text, 0, 200)
            ]);

            // تنظيف المخرجات من أي علامات تنسيق إضافية
            $cleanJson = $text;

            // Remove markdown code blocks (```json ... ```)
            $cleanJson = preg_replace('/```json\s*/i', '', $cleanJson);
            $cleanJson = preg_replace('/```\s*/', '', $cleanJson);
            $cleanJson = trim($cleanJson);

            // Remove any text before first [
            if (($pos = strpos($cleanJson, '[')) !== false) {
                $cleanJson = substr($cleanJson, $pos);
            }

            // Remove any text after last ]
            if (($pos = strrpos($cleanJson, ']')) !== false) {
                $cleanJson = substr($cleanJson, 0, $pos + 1);
            }

            $cleanJson = trim($cleanJson);

            Log::debug('Cleaned JSON', ['cleaned' => substr($cleanJson, 0, 500)]);

            $decoded = json_decode($cleanJson, true);

            // If JSON decode failed, try alternative extraction methods
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('First JSON decode attempt failed', [
                    'error' => json_last_error_msg(),
                    'cleaned_json_preview' => substr($cleanJson, 0, 500)
                ]);

                // Try to find JSON array using regex
                if (preg_match('/\[\s*\{.*\}\s*\]/s', $text, $matches)) {
                    $cleanJson = $matches[0];
                    $decoded = json_decode($cleanJson, true);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        Log::info('Successfully extracted JSON using regex');
                    }
                }

                // If still failed, try to fix common JSON issues
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Try to fix trailing commas
                    $cleanJson = preg_replace('/,\s*}/', '}', $cleanJson);
                    $cleanJson = preg_replace('/,\s*]/', ']', $cleanJson);
                    $decoded = json_decode($cleanJson, true);
                }

                // Final check
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Failed to decode Gemini JSON response after all attempts', [
                        'error' => json_last_error_msg(),
                        'json_error_code' => json_last_error(),
                        'original_response' => substr($text, 0, 1000),
                        'cleaned_json' => substr($cleanJson, 0, 1000)
                    ]);
                    return null;
                }
            }

            // Validate structure
            if (!is_array($decoded)) {
                Log::error('Gemini response is not an array', [
                    'decoded_type' => gettype($decoded),
                    'decoded' => $decoded
                ]);
                return null;
            }

            Log::info('Decoded questions', ['count' => count($decoded)]);

            // Filter and validate each question
            $validQuestions = [];
            foreach ($decoded as $index => $question) {
                if (!is_array($question)) {
                    Log::warning('Question is not an array', ['index' => $index, 'question' => $question]);
                    continue;
                }

                if ($this->validateQuestion($question)) {
                    $validQuestions[] = $question;
                } else {
                    Log::warning('Question validation failed', [
                        'index' => $index,
                        'question' => $question
                    ]);
                }
            }

            if (empty($validQuestions)) {
                Log::error('No valid questions generated', [
                    'total_received' => count($decoded),
                    'decoded' => $decoded
                ]);
                return null;
            }

            Log::info('Valid questions generated', ['count' => count($validQuestions)]);

            return $validQuestions;

        } catch (\Exception $e) {
            Log::error('Error generating questions with Gemini', [
                'topic' => $topic,
                'count' => $count,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw to let controller handle it with proper error message
            throw $e;
        }
    }

    /**
     * Validate a question structure
     * 
     * @param array $question
     * @return bool
     */
    protected function validateQuestion(array $question): bool
    {
        // Check required fields
        if (!isset($question['question']) || empty(trim($question['question']))) {
            return false;
        }

        if (!isset($question['options']) || !is_array($question['options'])) {
            return false;
        }

        // Must have exactly 4 options
        if (count($question['options']) !== 4) {
            return false;
        }

        // All options must be non-empty strings
        foreach ($question['options'] as $option) {
            if (empty(trim($option))) {
                return false;
            }
        }

        // Must have correct_answer
        if (!isset($question['correct_answer']) || empty(trim($question['correct_answer']))) {
            return false;
        }

        // Correct answer must match one of the options
        $correctAnswer = trim($question['correct_answer']);
        $hasMatchingOption = false;
        foreach ($question['options'] as $option) {
            if (trim($option) === $correctAnswer) {
                $hasMatchingOption = true;
                break;
            }
        }

        return $hasMatchingOption;
    }
}