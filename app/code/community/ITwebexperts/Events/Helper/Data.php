<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * Path to store config if front-end output is enabled
     *
     * @var string
     */
    const XML_PATH_ENABLED            = 'events/view/enabled';

    /**
     * Path to store config where count of events posts per page is stored
     *
     * @var string
     */
    const XML_PATH_ITEMS_PER_PAGE     = 'events/view/items_per_page';

    /**
     *
     */
    const XML_PATH_USE_EVENTS = 'events/global_calendar/use_events';

    /**
     *
     */
    const XML_PATH_USE_EVENT_DATES = 'events/global_calendar/use_event_dates';

    const XML_PATH_HOUR_PASS_DAY = 'events/global_calendar/hour_to_pass_day';

    /**
     * Path to store config where count of days while events is still recently added is stored
     *
     * @var string
     */
    const XML_PATH_DAYS_DIFFERENCE    = 'events/view/days_difference';


    const EVENT_ID = 'event_id';


    const GATE_NAME = 'gate_name';

    /**
     * Events Item instance for lazy loading
     *
     * @var ITwebexperts_Events_Model_Events
     */
    protected $_eventsItemInstance;

    /**
     * Checks whether events can be displayed in the frontend
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store);
    }

    /**
     * Return the number of items per page
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getEventsPerPage($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_ITEMS_PER_PAGE, $store));
    }

    /**
     * Function to check if events is enabled
     * @return bool
     */
    public static function useEvents()
    {
        if (Mage::getStoreConfig(self::XML_PATH_USE_EVENTS) != 0) {
            return true;
        }
        return false;
    }
    public static function hourPassDay($minDate)
    {
        $minDate = strtotime($minDate);
        if(Mage::getStoreConfig(self::XML_PATH_HOUR_PASS_DAY) != ''){
            $nextHour = strtotime(date('Y-m-d', Mage::getModel('core/date')->timestamp(time())) . Mage::getStoreConfig(self::XML_PATH_HOUR_PASS_DAY));
            $curTime = Mage::getModel('core/date')->timestamp(time());
            if($minDate < $curTime){
                $minDate = $curTime;
            }
            if ($curTime > $nextHour) {
                $minDate = strtotime('+1 day', $minDate);
            }
        }
        return date('Y-m-d', $minDate);
    }

    /**
     * Function to check if event dates is enabled
     * @return bool
     */
    public static function useEventDates()
    {
        if (Mage::getStoreConfig(self::XML_PATH_USE_EVENT_DATES) != 0) {
            return true;
        }
        return false;
    }

    /**
     * Return difference in days while events is recently added
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getDaysDifference($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_DAYS_DIFFERENCE, $store));
    }

    /**
     * Return current events item instance from the Registry
     *
     * @return ITwebexperts_Events_Model_Events
     */
    public function getEventsItemInstance()
    {
        if (!$this->_eventsItemInstance) {
            $this->_eventsItemInstance = Mage::registry('events_item');

            if (!$this->_eventsItemInstance) {
                Mage::throwException($this->__('Events item instance does not exist in Registry'));
            }
        }

        return $this->_eventsItemInstance;
    }

    public function getEventsDropdownHtml($passed_date = false, $selected = 0){
        $collection = Mage::getModel('itwebexperts_events/events')->getCollection();

        if($passed_date){
            //$collection->addAttributeToFilter('start_date >= ?', date('Y-m-d H:i:s'));
            $collection->addFieldToFilter ('end_date', array(
                'from' => date('Y-m-d H:i:s'),
                'date' => true, // specifies conversion of comparison values
            ));
        }
        //should have a selected too for when saving into cookie he event id and gate and dates
        $html = '';
        if($collection){
            $html .= '<select name="event_id" class="eventId">';
            foreach ($collection as $item) {
                $html .= '<option value="'.$item->getEventsId().'" '.(($item->getEventsId() == $selected)?'selected="selected"':'').'>'.$item->getTitle().'</option>';
            }
            $html .= '</select>'.'<span class="icon1 eventHelp" style="display:inline-block;vertical-align:middle;"></span>';
        }
        return $html;
    }

    public function getEventNameById($event_id){
        $model = Mage::getModel('itwebexperts_events/events')->load($event_id);
        return $model->getTitle();
    }

    public function getGatesDropdownHtmlForEvent($event_id, $selected){
        $model = Mage::getModel('itwebexperts_events/events')->load($event_id);
        $gatesString = $model->getGates();
        $gates = explode(',', $gatesString);
        $html = '';
        if(count($gates) > 0 && $gates[0] != ''){
            $html .= '<select name="gate_name" class="gateName">';
            foreach ($gates as $item) {
                $html .= '<option value="'.$item.'" '.(($item == $selected)?'selected="selected"':'').'>'.$item.'</option>';
            }
            $html .= '</select>';
        }
        return $html;
    }

    public function getEventStartdateById($event_id){
        $model = Mage::getModel('itwebexperts_events/events')->load($event_id);
        return $model->getStartDate();
    }

    public function getEventDescription($event_id){
        $model = Mage::getModel('itwebexperts_events/events')->load($event_id);
        return $model->getDetails();
    }

    public function getEventEnddateById($event_id){
        $model = Mage::getModel('itwebexperts_events/events')->load($event_id);
        return $model->getEndDate();
    }

}
