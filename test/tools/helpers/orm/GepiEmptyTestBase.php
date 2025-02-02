
<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

require_once 'GepiTestBase.php';
require_once 'GepiDataPopulator.php';

/**
 * Base class contains some methods shared by subclass test cases.
 */
abstract class GepiEmptyTestBase extends GepiTestBase
{
	/**
	 * This is run before each unit test; it empties the database.
	 */
	protected function setUp()
	{
		parent::setUp();
		GepiDataPopulator::depopulate($this->con);
	}

}
