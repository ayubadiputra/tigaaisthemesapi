<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article class='page type-page status-publish format-standard hentry'>
            <header class="entry-header">
		        <h3>Submit Your Application Data</h3>
		        <?php
		        if(Flash::has('message')){
		            echo "<div class='tiga-alert ".Flash::get('type')."'>";
		            echo Flash::get('message');
		            echo "</div>";
		        }
		        ?>
		    </header><!-- .entry-header -->
            <div class="entry-content">
                <?php
                    $options = array(
                        'action' => tiga_url( '/custom-form' ),
                        'method' => 'POST'
                    );
                    echo Form::open($options);
                    echo Form::label('name', 'Your Name', array('class' => 'awesome'));
                    echo Form::text('name','',array('placeholder'=>'Your Surename'));
                    echo Form::label('description', 'Description');
                    echo Form::wpEditor("description",'', array('textarea_rows'=>20));
                    echo Form::submit('Submit!');
                    echo Form::close();
                ?>
            </div>
        </article>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>