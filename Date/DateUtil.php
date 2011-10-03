<?php

namespace Mzz\MzzBundle\Date;

class DateUtil
{

    /**
     * @todo time
     *
     * @param array $datetime
     * @return \DateTime
     */
    public static function createFromArray(array $datetime)
    {
        $time = '00:00:00';
        if (isset($datetime['hour']) && isset($datetime['minute']))
            $time = sprintf('%02d:%02d:00', $datetime['hour'], $datetime['minute']);
        $date = $datetime['year'] . '-' . $datetime['month'] . '-' . $datetime['day'];

        if ($date == '--')
            return null;

        $dt = new \DateTime($date . ' ' . $time);

        // Only check for warnings because if an error occurs, the DateTime::__construct
        // will throw an exception.
        if (($errors = \DateTime::getLastErrors()) && $errors['warning_count'] > 0)
            throw new \InvalidArgumentException('The parsed date was invalid');
        return $dt;
    }

}
