<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

?>
<header id="content-header">
	<h2><?php echo $this->escape($this->product->pName); ?></h2>

	<div id="content-header-extra">
		<p>
			<a class="btn" href="/cart"><?php echo Lang::txt('COM_STOREFRONT_CART'); ?></a>
		</p>
	</div>
</header>

<?php
if (!empty($this->notifications))
{
	$view = new \Hubzero\Component\View(array('name'=>'shared', 'layout' => 'notifications'));
	$view->notifications = $this->notifications;
	$view->display();
}
?>

<section class="section">
	<div class="section-inner">

		<div class="grid">
			<div class="col span6">
				<div class="productImages">
					<?php
					$imgPath = '/app/' . trim($this->config->get('imagesFolder', '/site/storefront/products'), DS) . DS . $this->pId . DS;

					if (empty($this->product->images) || !is_file(PATH_ROOT . $imgPath . $this->product->images[0]->imgName))
					{
						$imgPath = dirname(dirname(dirname(str_replace(PATH_ROOT, '', __DIR__)))) . DS . 'assets' . DS . 'img' . DS;
						$image = new \stdClass();
						$image->imgName = 'noimage.png';
						$this->product->images[0] = $image;
					}

					if (!strstr($this->product->images[0]->imgName, 'noimage'))
					{
						echo '<a href="' . $imgPath . $this->product->images[0]->imgName . '" rel="lightbox">';
					}
					echo '<img src="' . $imgPath . $this->product->images[0]->imgName . '" alt="' . $this->escape($this->product->pName) . '" />';
					if (!strstr($this->product->images[0]->imgName, 'noimage'))
					{
						echo '</a>';
					}
					?>
				</div>
			</div>
			<div class="col span6 omega">
				<?php
				// format price/price range
				$price = $this->price;
				$priceRange = '';

				if ($price['high'] == $price['low'])
				{
					$priceRange .=  '$' . number_format($price['high'], 2);
				}
				else
				{
					$priceRange .= '$' . number_format($price['low'], 2) . ' &ndash; ' . '$' . number_format($price['high'], 2);
				}

				$out = false;
				if (!$this->inStock)
				{
					$priceRange = 'Out of stock';
					$out = true;
				}
				?>
				<?php if (empty($this->statusMessage) || $this->statusMessage != 'restricted') { ?>
					<div id="price" class="<?php echo $out ? 'outofstock' : ''; ?>">
						<?php echo $priceRange; ?>
					</div>
				<?php } ?>

				<form id="productInfo" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					<input type="hidden" name="pId" value="<?php echo $this->pId; ?>" />

					<?php if (isset($this->options) && count($this->options)) { ?>
						<div id="productOptions">
							<?php foreach ($this->options as $optionGroupId => $info) { ?>
								<p class="option-label"><?php echo $info['info']->ogName; ?>:</p>
								<ul class="product-options">
									<?php
									foreach ($info['options'] as $opt)
									{
										echo '<li><input type="radio" name="og[' . $optionGroupId . ']" value="' . $opt->oId . '" id="option_' . $opt->oId . '">';
										echo '<label for="option_' . $opt->oId . '">' . $opt->oName . '</label></li>';
									}
									?>
								</ul>
							<?php } ?>
						</div>
					<?php } ?>

					<?php
						// Quantity text
						if (!empty($this->meta->qtyTxt))
						{
							$qtyTxt = $this->meta->qtyTxt;
						}
						elseif ($this->config->get('quantityText'))
						{
							$qtyTxt = $this->config->get('quantityText');
						}
						else {
							$qtyTxt = Lang::txt('Quantity');
						}

					?>

					<div id="qtyWrap" data-label="<?php echo $qtyTxt; ?>">
						<?php
						$addToCartEnabled = false;
						if ($this->qtyDropDown)
						{
							$addToCartEnabled = true;
							if ($this->qtyDropDown > 1)
							{
								echo '<div class="inner">';
								echo '<label for="qty">' . $qtyTxt . '</label> ';
								echo '<select name="qty" id="qty">';
								for ($i = 1; $i <= $this->qtyDropDown; $i++)
								{
									echo '<option value="' . $i . '">' . $i . '</option>';
								}
								echo '</select>';
								echo '</div>';
							}
						}
						?>
					</div>

					<?php if ($this->inStock && $this->productAvailable) { ?>
						<p class="submit">
							<input type="submit" value="Add to cart"
								   class="btn <?php  echo $addToCartEnabled ? 'enabled' : 'disabled'; ?>"
								   name="addToCart" id="addToCart" />
						</p>
					<?php } ?>
				</form>

				<h3><?php echo $this->product->pTagline; ?></h3>

				<div class="description">
					<?php echo $this->product->pDescription; ?>
				</div>

				<?php if (!empty($this->product->pFeatures)) { ?>
					<div class="features">
						<?php echo $this->product->pFeatures; ?>
					</div>
				<?php } ?>

				<?php
				/*foreach ($this->product as $k => $val)
				{
					echo '<p>' . $k . ': ' . $val . '</p>';
				}*/
				?>
			</div>
		</div>
	</div>
</section>