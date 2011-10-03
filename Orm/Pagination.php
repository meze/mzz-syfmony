<?php

namespace Mzz\MzzBundle\Orm;

/**
 * Implements a simple pagination mechanism: previous page / next page.
 *
 * Note on the current page parameter. It accepts any integer number, even greater than amount of available pages.
 * The value will be sanitized by adjusting it to the closest valid value before it is set. The reason why
 * it does not throw an exception is because this parameter comes from the user input and when they passed
 * invalid value as a current page, the most acceptable behaviour would be "adjust" its value.
 *
 *
 */
class Pagination
{
    private $total = 0;
    private $perPage = 0;
    private $currentPage = 0;

    /**
     *
     * @see self::setCurrentPage()
     *
     * @param integer $per_page
     */
    public function __construct($per_page)
    {
        $this->perPage = max((int)$per_page, 0);
    }

    /**
     * Returns number of items displayed on each page
     *
     * @return integer
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Returns current page. Can't be greater than the last page (if it is so, the closest page is returned).
     *
     * @return integer
     */
    public function getCurrentPage()
    {
        return min($this->currentPage, $this->getLastPage());
    }

    /**
     * Calculates offset (items to skip). Can't be greater than total items (if there are 100 items,
     * and per page, current page are set to 10 and 10, respectively, than the offset will be 90, not
     * 100 since there no item to display after 100 skipped)
     *
     * @return integer
     */
    public function getOffset()
    {
        if (!$this->hasPages())
            return 0;
        return $this->perPage * ($this->getCurrentPage() - 1);
    }

    /**
     * Returns total number of paginated items
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * The last page.
     *
     * @return integer
     */
    public function getLastPage()
    {
        if ($this->perPage === 0 || $this->total === 0)
            return 0;
        return ceil($this->total / $this->perPage);
    }

    /**
     * Returns the previous page. Can't be less than one
     *
     * @return integer
     */
    public function getPreviousPage()
    {
        return max($this->currentPage - 1, 1);
    }

    /**
     * Returns the next page. Can't be greater than the last page
     *
     * @return integer
     */
    public function getNextPage()
    {
        return min($this->currentPage + 1, $this->getLastPage());
    }

    /**
     * Returns true if there are more pages available
     *
     * @return boolean
     */
    public function hasNextPage()
    {
        return $this->currentPage < $this->getLastPage();
    }

    /**
     * Returns true if the previous page exists
     *
     * @return boolean
     */
    public function hasPreviousPage()
    {
        return $this->currentPage > 1;
    }

    /**
     * Returns true if there is more than one page
     *
     * @return boolean
     */
    public function hasPages()
    {
        return $this->getLastPage() > 1;
    }

    /**
     * Sets the current page.
     *
     * @param integer $page
     */
    public function setCurrentPage($page)
    {
        $this->currentPage = max((int)$page, 0);
    }

    /**
     * Sets the number of items that are paginated
     *
     * @param integer $total
     */
    public function setTotal($total)
    {
        $this->total = max((int)$total, 0);
    }

}
