<?php

namespace Core;

class TemplateEngine
{
    public array $variables = [];

    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }

    public function render(string $template): string
    {
        if (!file_exists($template)) {
            throw new \Exception("Component file not found: $template");
        }

        $content = file_get_contents($template);

        $content = $this->variables($content);
        $content = $this->form($content);
        $content = $this->condition($content);
        $content = $this->foreach($content);
        $content = $this->for($content);

        return $content;
    }

    public function variables(string $content): string
    {
        $content = preg_replace_callback('`\{{\s([^}[]+)((\[[^}]+)*)\s}}`', function ($matches) {
            return $this->variables[$matches[1]];
        }, $content);

        return $content;
    }

    public function condition(string $content): string
    {
        $content = preg_replace_callback('/\{#\s*if\s*\(([^)]+)\)\s*\#\}(.*?)\{#\s*else\s*\#\}(.*?)\{#\s*endif\s*\#\}/s', function ($matches) {
            $condition = $matches[1];
            $blockContent = $matches[2];
            $elseContent = $matches[3];
    
            preg_match('/var=(\w+)\s*===\s*(true|false)/', $condition, $variableMatch);
            if (count($variableMatch) === 3) {
                $variableName = $variableMatch[1];
                $variableValue = $variableMatch[2];
    
                if (isset($this->variables[$variableName]) && ($this->variables[$variableName] === $variableValue || ($variableValue === 'true' && $this->variables[$variableName]))) {
                    return $blockContent;
                } else {
                    return $elseContent;
                }
            }
    
            return '';
        }, $content);
    
        return $content;
    }

    public function foreach(string $content): string
    {
        $content = preg_replace_callback('/\{#\s*foreach\s*\((\w+)\s+as\s+(\w+)\s*=>\s*(\w+)\s*\)\s*#\}(.*?)\{#\s*endforeach\s*\#\}/s', function ($matches) {
            $array = $matches[1] ?? '';
            $key = $matches[2] ?? '';
            $value = $matches[3] ?? '';
            $body = $matches[4] ?? '';
            
            $foreachResult = '';
            foreach ($this->variables[$array] as $item) {
                $tempBody = $body;
                if (is_array($item)) {
                    foreach ($item as $key => $value) {
                        $tempBody = preg_replace('/\{echo_var=' . preg_quote($key, '/') . '\}/', $value, $tempBody);
                    }
                } else {
                    $tempBody = preg_replace('/\{echo_var=' . preg_quote($key, '/') . '\}/', $item, $tempBody);
                }
                $foreachResult .= $tempBody;
            }
    
            return $foreachResult;
        }, $content);
    
        return $content;
    }

    public function form(string $content): string
    {
        $content = preg_replace_callback('/@(form|label|input)\s+((?:\w+\s*=\s*{[^}]*}\s*)*)/', function ($matches) {
            $tag = $matches[1];
            $attributesString = $matches[2];
            preg_match_all('/(\w+)\s*=\s*{([^}]*)}/', $attributesString, $attributeMatches, PREG_SET_ORDER);
            $attributes = [];

            foreach ($attributeMatches as $attributeMatch) {
                $key = trim($attributeMatch[1]);
                $value = trim($attributeMatch[2], '{}');
                $attributes[$key] = $value;
            }

            $tagString = "<$tag ";
            foreach ($attributes as $key => $value) {
                if ($key === 'value' && $tag === 'label') {
                    break;
                }
                $tagString .= "$key=\"$value\" ";
            }
            $tagString .= '>';

            if ($key === 'value' && $tag === 'label') {
                $tagString .= $value;
            }

            return $tagString;
        }, $content);

        $content = preg_replace('/@endform/', '</form>', $content);
        $content = preg_replace('/@endlabel/', '</label>', $content);

        return $content;
    }

    public function for(string $content): string
    {
        $content = preg_replace_callback('/{#\sfor\s*\(([^)]+)\)\s*#}(.*?)@endfor/s', function ($matches) {
            $init = trim($matches[1]);
            $body = $matches[2] ?? '';

            preg_match('/\$(\w+)\s*=\s*(.*?);/', $init, $initMatches);
            $variable = $initMatches[1] ?? '';
            $condition = $initMatches[2] ?? '';

            preg_match('/\$(\w+)\+\+/', $condition, $incrementMatches);
            $incrementVariable = $incrementMatches[1] ?? '';

            $loopCondition = false;

            eval('$loopCondition = (' . $condition . ');');
    
            $forResult = '';
            for ($$variable; $loopCondition; $$incrementVariable) {
                $tempBody = $body;
                $tempBody = str_replace('{{ $' . $variable . ' }}', $$variable, $tempBody);
    
                $forResult .= $tempBody;

                eval('$loopCondition = (' . $condition . ');');
            }
    
            return $forResult;
        }, $content);
    
        return $content;
    }
}
