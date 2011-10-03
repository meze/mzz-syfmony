<div class="errors<?php echo $form->getErrors() == '' ? ' hidden' : ''?>">
<h2>Oops, there are some errors.</h2>


    <ul>
        <?php foreach ($form->getErrors() as $error): ?>
             <li><?php echo $view['translator']->trans(
                $error->getMessageTemplate(),
                $error->getMessageParameters(),
                'validators'
            ) ?></li>
        <?php endforeach; ?>
    </ul>

</div>

<form action="<?php echo $view['router']->generate('login')?>" method="post">

    <?php echo $view['form']->hidden($form) ?>

<input type="hidden" value="<?php echo htmlspecialchars($redirectTo); ?>" name="redirect_to"/>
     <?php echo $view['form']->render($form['username']) ?><br />
     <?php echo $view['form']->render($form['password']) ?>
     <input type="submit" value="Log in" />
</form>
</div>