<?php

namespace App\Models;

class ProductCategoryClassifierModel
{
    /**
     * Returns a normalized category label for product metadata.
     */
    public function classify(array $input): string
    {
        $text = strtolower(trim(implode(' ', [
            (string) ($input['title'] ?? ''),
            (string) ($input['description'] ?? ''),
            (string) ($input['product_feature'] ?? ''),
            (string) ($input['how_it_works'] ?? ''),
            (string) ($input['redirect_url'] ?? ''),
        ])));

        // Enrich text with keywords derived from uploaded file extensions.
        // This allows e.g. an Excel file to be classified as Business & Finance
        // even when the text description doesn't mention it explicitly.
        $extensionKeywords = [
            'xlsx'   => 'excel spreadsheet',
            'xls'    => 'excel spreadsheet',
            'xlsm'   => 'excel spreadsheet',
            'xlsb'   => 'excel spreadsheet',
            'csv'    => 'spreadsheet',
            'ods'    => 'spreadsheet',
            'pptx'   => 'powerpoint presentation slides',
            'ppt'    => 'powerpoint presentation slides',
            'key'    => 'presentation slides',
            'pdf'    => 'ebook pdf book',
            'epub'   => 'ebook',
            'mobi'   => 'ebook',
            'docx'   => 'document template',
            'doc'    => 'document template',
            'odt'    => 'document template',
            'zip'    => 'bundle creative pack',
            'rar'    => 'bundle creative pack',
            '7z'     => 'bundle creative pack',
            'fig'    => 'figma design',
            'psd'    => 'design',
            'ai'     => 'design illustration',
            'svg'    => 'design illustration',
        ];

        foreach ((array) ($input['file_paths'] ?? []) as $path) {
            $ext = strtolower(pathinfo((string) $path, PATHINFO_EXTENSION));
            if (isset($extensionKeywords[$ext])) {
                $text .= ' ' . $extensionKeywords[$ext];
            }
        }

        $text = trim($text);

        if ($text === '') {
            return 'Templates';
        }

        $categoryKeywords = [
            'E-books' => [
                'ebook', 'e-book', 'book', 'pdf book', 'guidebook', 'kindle',
            ],
            'Printables' => [
                'printable', 'planner', 'worksheet', 'checklist', 'calendar', 'journal', 'print',
            ],
            'Design Assets' => [
                'design', 'icon', 'mockup', 'ui kit', 'illustration', 'font', 'logo', 'figma', 'canva elements',
            ],
            'Marketing Materials' => [
                'marketing', 'social media', 'ads', 'ad copy', 'campaign', 'promotion', 'brochure', 'flyer',
            ],
            'Business & Finance Tools' => [
                'business', 'finance', 'budget', 'invoice', 'cash flow', 'startup', 'proposal', 'pitch deck',
                'excel', 'spreadsheet', 'accounting', 'financial model', 'kpi', 'dashboard', 'report',
            ],
            'Creative Packs' => [
                'creative pack', 'bundle', 'asset pack', 'creator', 'storyboard', 'brand kit',
            ],
            'Study & Productivity' => [
                'study', 'productivity', 'notion', 'tracker', 'course notes', 'exam', 'student',
            ],
            'Templates' => [
                'template', 'theme', 'preset', 'layout', 'document',
            ],
            'Presentation Slides' => [
                'presentation', 'powerpoint', 'ppt', 'slides', 'google slides',
            ],
        ];

        $scores = [];
        foreach ($categoryKeywords as $category => $keywords) {
            $scores[$category] = 0;
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $scores[$category]++;
                }
            }
        }

        arsort($scores);
        $topCategory = array_key_first($scores);
        $topScore = $topCategory !== null ? (int) ($scores[$topCategory] ?? 0) : 0;

        if ($topScore <= 0) {
            return 'Templates';
        }

        return (string) $topCategory;
    }
}
