<?php
/**
 * Archive: Organizing Groups
 */
get_header(); ?>

<div class="hero hero--short">
    <div class="container">
        <div class="hero__content">
            <span class="hero__eyebrow"><?php esc_html_e( 'Community Network', 'faithfulwitness' ); ?></span>
            <h1><?php esc_html_e( 'Organizing Groups', 'faithfulwitness' ); ?></h1>
            <p><?php esc_html_e( 'Find community organizers working on immigrant justice near you.', 'faithfulwitness' ); ?></p>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">

        <!-- State filter -->
        <div style="margin-bottom:var(--space-6);display:flex;align-items:center;gap:var(--space-4);flex-wrap:wrap;">
            <?php
            $states = get_terms( [ 'taxonomy' => 'fw_state', 'hide_empty' => true ] );
            if ( $states && ! is_wp_error( $states ) ) :
            ?>
            <label for="state-filter" class="sr-only"><?php esc_html_e( 'Filter by state', 'faithfulwitness' ); ?></label>
            <select id="state-filter" class="form-control" style="max-width:200px;">
                <option value=""><?php esc_html_e( 'All states', 'faithfulwitness' ); ?></option>
                <?php foreach ( $states as $s ) : ?>
                <option value="<?php echo esc_url( get_term_link( $s ) ); ?>"><?php echo esc_html( $s->name ); ?></option>
                <?php endforeach; ?>
            </select>
            <script>
            document.getElementById('state-filter').addEventListener('change', function() {
                if (this.value) window.location.href = this.value;
            });
            </script>
            <?php endif; ?>
        </div>

        <?php if ( have_posts() ) : ?>
        <div class="grid grid--3">
            <?php while ( have_posts() ) : the_post();
                get_template_part( 'template-parts/content', 'organizing-group-card', [ 'post' => $post ] );
            endwhile; ?>
        </div>
        <?php the_posts_pagination( [ 'class' => 'pagination' ] ); ?>
        <?php else : ?>
        <p><?php esc_html_e( 'No organizing groups found.', 'faithfulwitness' ); ?></p>
        <?php endif; ?>

    </div>
</section>

<?php get_footer();
