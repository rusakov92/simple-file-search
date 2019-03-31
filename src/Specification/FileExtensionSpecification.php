<?php
/**
 * This file is part of the Reward Gateway package.
 *
 * (c) Reward Gateway <copyright@rewardgateway.com>
 */

namespace App\Specification;

/**
 * Class FileExtensionSpecification.
 *
 * @author Aleksandar Rusakov <aleksandar.rusakov@rewardgateway.com>
 */
class FileExtensionSpecification
{
    private const SUPPORTED_EXTENSIONS = [
        'txt',
    ];

    /**
     * Check if the given extension is supported.
     *
     * @param string $extension
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function isSatisfiedBy(string $extension) : string
    {
        $formattedExtension = \mb_strtolower(\trim($extension, '.'));

        if (\in_array($formattedExtension, self::SUPPORTED_EXTENSIONS, true)) {
            return $formattedExtension;
        }

        throw new \InvalidArgumentException(\sprintf('Extension "%s" is not supported.', $extension));
    }
}
