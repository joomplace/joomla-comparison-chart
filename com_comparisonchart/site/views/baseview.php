<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * @subpackage	ComparisonChart
 */
class BaseView extends JViewLegacy
{
	public function display($tpl = null)
	{

		// add html helpers
		$this->addTemplatePath(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'html');

		// update document
		$this->setDocument();

		// update pathway
		$this->setPathwayCategory();
		$this->setPathway();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	public function setDocument()
	{
		$doc =$this->document;
	}

	/**
	 * Prepares the document
	 */
	public function setPathway()
	{
	}

	/**
	 * Prepares the categories pathway
	 */
	public function setPathwayCategory()
	{
		$app =JFactory::getApplication();
		$pathway =$app->getPathway();

		$hash = '';
		$path = array();

        $jsite = new JSite;
		$menu = $jsite->getMenu();
		$active = $menu->getActive();
		if (!$active or ($active->component != 'com_comparisonchart')) {
			$menuitem = $menu->getItems('component', 'com_comparisonchart', true);
			if ($menuitem) {
				$hash = '&Itemid='.$menuitem->id;
				$pathway->addItem($menuitem->title, JRoute::_($menuitem->link.$hash));
			}
		}

		if (isset($this->category)) {
            if (!$this->category->id) $this->category->id = 0;
				$parent = $this->category->parents[$this->category->parent_id];
				while ($parent->id > 1) {
					array_unshift($path, array(
						'title' => $parent->title,
						'link' => 'index.php?option=com_comparisonchart&view=category&catid='.$parent->id.'&id='.JRequest::getInt('id')
					));
					$parent = $this->category->parents[$parent->parent_id];
				}
				
				$path[] = array(
					'title' => $this->category->title,
					'link' => 'index.php?option=com_comparisonchart&view=category&catid='.$this->category->id.'&id='.JRequest::getInt('id')
				);

				foreach ($path as $item) {
					$pathway->addItem($item['title'], JRoute::_($item['link'].$hash));

			}
		}
	}

	public function getAccess($actions, $asset = 'com_comparisonchart')
	{
		$result	= new JObject;

		foreach ($actions as $action) {
			$result->set($action, (int)$this->user->authorise($action, $assetName));
		}

		return $result;
	}
}
?>