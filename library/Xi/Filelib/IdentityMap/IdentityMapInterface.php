<?php

/**
 * This file is part of the Xi Filelib package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Filelib\IdentityMap;

use Xi\Filelib\IdentityMap\Identifiable;
use Xi\Filelib\IdentityMap\IdentityMapException;
use Iterator;

interface IdentityMapInterface
{
    /**
     * Returns whether identity map has an identifiable
     *
     * @param Identifiable $object
     * @return bool
     */
    public function has(Identifiable $object);

    /**
     * Adds an identifiable to identity map
     *
     * @param Identifiable $object
     * @throws IdentityMapException When identifiable can not be identified
     * @return bool
     */
    public function add(Identifiable $object);

    /**
     * Adds many identifiables to identity map
     *
     * @param Iterator $iterator
     */
    public function addMany(Iterator $iterator);

    /**
     * Removes many identifiables from identity map
     *
     * @param Iterator $iterator
     */
    public function removeMany(Iterator $iterator);

    /**
     * Removes an identifiable
     *
     * @param Identifiable $object
     * @return bool
     */
    public function remove(Identifiable $object);

    /**
     * Gets an identifiable by id and class name
     *
     * @param mixed $id
     * @param string $className
     * @return Identifiable|false
     */
    public function get($id, $className);
}
