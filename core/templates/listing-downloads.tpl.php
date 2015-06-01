<ul class="gre-listing-downloads">
<?php foreach ( $downloads as $d ): ?>
    <li>
        <a href="<?php echo esc_url( $d->url ); ?>"><?php echo $d->filename; ?></a>
        <?php if ( ! $d->remote ): ?>
        <div class="file-info">
            <span class="file-size"><?php echo size_format( $d->size ); ?></span>
        </div>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
