<?php
$head_text   = $section['heading_text'] ?? '';
$head_tag    = $section['heading_tag'] ?? 'h2';
$head_align  = $section['heading_alignment'] ?? 'left';
$head_color  = $section['heading_color'] ?? '';
$head_size   = $section['heading_size'] ?? '';
$sub_head    = $section['sub_heading'] ?? '';
$collage1    = $section['collage_content'] ?? [];
$collage2    = $section['collage_content2'] ?? [];
$section_id = preg_replace('/\s+/', '', $section['section_id']) ?: '';

// Prepare sanitized attributes
$section_id_attr = $section_id ? ' id="' . esc_attr($section_id) . '"' : '';

// Dynamically build style attribute only if values exist
$styles = [];
if (!empty($head_align)) {
    $styles[] = 'text-align:' . esc_attr($head_align);
}
if (!empty($head_size)) {
    $styles[] = 'font-size:' . esc_attr($head_size) . 'px';
}
if (!empty($head_color)) {
    $styles[] = 'color:' . esc_attr($head_color);
}

$style_attr = !empty($styles) ? ' style="' . implode('; ', $styles) . ';"' : '';
?>

<section<?php echo $section_id_attr; ?> class="full-section dark additional_module" role="region" aria-labelledby="<?php echo esc_attr($section_id . '-heading'); ?>">
	<div class="container">

		<?php if ($sub_head): ?>
			<div class="subHeading"><span><?php echo esc_html($sub_head); ?></span></div>
		<?php endif; ?>

		<?php if ($head_text): ?>
			<?php
			printf(
				'<%1$s class="section-heading"%3$s>%4$s</%1$s>',
				tag_escape($head_tag),
				esc_attr($section_id),
				$style_attr,
				esc_html($head_text)
			);
			?>
		<?php endif; ?>

		<?php
		/**
		 * Helper function to render collage blocks.
		 */
		function render_collage_items(array $items, int $type = 1, $pageIndex = ''): void {
			if (empty($items)) {
				return;
			}

			printf('<ul class="collage collage-%d" role="list">', $type);

			foreach ($items as $key => $item) {
				$align     = $item['image_alignment'] ?? '';
				$width     = $item['box_width'] ?? '';
				$image_id  = $item['image'] ?? '';
				$sub_head  = $item['sub_heading'] ?? '';
				$head      = $item['heading_text'] ?? '';
				$content   = $item['content'] ?? '';
				$button    = $item['button'] ?? null;

				$li_class = $type === 1
					? esc_attr($align . '_image')
					: '' . esc_attr($width);

				echo '<li class="' . $li_class . '">';

				if ($image_id) {
					echo '<figure class="am_image">';
					// echo wp_get_attachment_image($image_id, 'full', false, [
					// 	'alt' => esc_attr($head ?: $sub_head ?: 'Decorative image'),
					// 	'loading' => 'lazy',
					// ]);
					echo getFocalImage($image_id, 'image', $pageIndex, $key);
					echo '</figure>';
				}

				echo '<div class="am_content">';
					echo '<div class="am_con">';
						if ($sub_head) {
							printf('<span class="am_subheading">%s</span>', esc_html($sub_head));
						}
						if ($head) {
							printf('<h3 class="am_heading">%s</h3>', esc_html($head));
						}
						if ($content) {
// 						    $limited_content = wp_trim_words($content, 53, '...');
// 						    echo wp_kses_post(wpautop($limited_content));
							echo WysiwygReadMoreLess($content);
						}

						if (!empty($button) && !empty($button['url'])) {
							$link_url    = esc_url($button['url']);
							$link_title  = esc_html($button['title'] ?? 'Learn more');
							$link_target = !empty($button['target']) ? esc_attr($button['target']) : '_self';

							printf(
								'<a class="btn" href="%s" target="%s" rel="noopener noreferrer">%s</a>',
								$link_url,
								$link_target,
								$link_title
							);
						}
					echo '</div>'; //am_con
				echo '</div>'; // .am_content
				echo '</li>';
			}
			echo '</ul>';
		}

		// Render both collages
		if (!empty($collage1)) {
			echo '<div class="collage1">';
			render_collage_items($collage1, 1, $pageIndex);
			echo '</div>';
		}

		if (!empty($collage2)) {
			echo '<div class="collage2">';
			render_collage_items($collage2, 2, $pageIndex);
			echo '</div>';
		}
		?>
	</div>
</section>
