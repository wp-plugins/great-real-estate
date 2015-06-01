<li data-download-index="<?php echo $download->index; ?>">
    <div>
        <a href="<?php echo $download->url; ?>" class="filename"><?php echo $download->filename; ?></a>
        <div class="item-actions">
            <a href="#" class="delete">Delete</a>
        </div>
    </div>
    <div class="fileinfo"><?php echo size_format( $download->size ); ?></div>

    <?php if ( $download->description ): ?>
    <div class="description"><?php echo $download->description; ?></div>
    <?php endif; ?>
</li>

