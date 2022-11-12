<?php
	$args = array(
		'post_type' => 'metathumb',
	);
	$the_query = new WP_Query( $args ); ?>

	<?php if ( $the_query->have_posts() ) : ?>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post();
			$post_ID = get_the_id();  ?>

			<div class="card_content">
					<a href="#" class="card_content-link ajax-post" id="<?php echo $post_ID ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
						<div class="card_content-img">
							<?php the_post_thumbnail(); ?>
						</div>
						<div class="card_content-description">
							<div class="card_info">
								<h2 class="card_info-title">
								<?php the_title(); ?>
								</h2>
								<span class="card_info-desc">
									<?php the_excerpt(); ?>
								</span>
							</div>
							<div class="card_logo">
								<?php
								$attachment_id = get_post_meta($post->ID, 'uploader_custom', true);
								$attributes = wp_get_attachment_image_src( $attachment_id, array(32, 32) );
								?>
								<?php echo '<img src="' . $attributes[0] . '" />'; ?>
							</div>
						</div>
					</a>
			</div>

			<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p><?php _e( 'Постов нет.' ); ?></p>
<?php endif; ?>

		<!-- Модальное окно карточки -->
		<div class="popup">
			<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						<div id="ajax-response" class="modal-cart">
						</div>
					</div>
				</div>
			</div>
		</div>