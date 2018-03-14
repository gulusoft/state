<?php
class InterfaceGenerator
{
    /**
     * @param array $operations
     * @param string $interfaceName
     */
    public function generate(array $operations, $interfaceName, $namespaceName)
    {
        $buffer   = '';
        $template = file_get_contents(new TemplateFilename('InterfaceMethod'));

        foreach (array_keys($operations) as $operation) {
            $buffer .= str_replace('___METHOD___', $operation, $template);
        }

        file_put_contents(
            new CodeFilename($interfaceName),
            str_replace(
                array(
                    '___INTERFACE___',
                    '___METHODS___',
                    '___NAME_SPACE___'
                ),
                array(
                    $interfaceName,
                    $buffer,
                    $namespaceName
                ),
                file_get_contents(new TemplateFilename('Interface'))
            )
        );
    }
}
