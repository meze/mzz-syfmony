<nav class="pager separator">
    <?php if ($pagination->hasPages()) { ?>
    <span>Page <?php echo $pagination->getCurrentPage(); ?> of <?php echo $pagination->getLastPage(); ?></span>
    <div class="pages">

        <span class="prev">
            <?php if ($pagination->hasPreviousPage()) { ?>
                <a href="<?php echo $view['router']->generate('news_list', array('page' => $pagination->getPreviousPage())); ?>"><?php echo $pagination->getPreviousPage(); ?></a>
            <?php } else { ?>
                <span><?php echo $pagination->getCurrentPage(); ?></span>
            <?php } ?>
        </span>


        <span class="next">
            <?php if ($pagination->hasNextPage()) { ?>
                <a href="<?php echo $view['router']->generate('news_list', array('page' => $pagination->getNextPage())); ?>"><?php echo $pagination->getNextPage(); ?></a>
            <?php } else { ?>
                <span><?php echo $pagination->getLastPage(); ?></span>
            <?php } ?>
        </span>
    </div>
    <?php } else { ?>
    <span>Only 1 page</span>
    <?php } ?>
</nav>
