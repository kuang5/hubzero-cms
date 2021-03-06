<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

$this->css('reports')
     ->css('impact.css', 'projects', 'publications');

// Common options for js charts
$options = "
xaxis: { ticks: xticks },
yaxis: { ticks: [[0, ''], [yTickSize, yTickSize]], color: 'transparent', tickDecimals:0, labelWidth: 0 },
series: {
	lines: {
		show: true,
		fill: true
	},
	points: { show: true },
	shadowSize: 0
},
grid: {
	color: 'rgba(0, 0, 0, 0.6)',
	borderWidth: 0,
	borderColor: 'transparent',
	hoverable: hover,
	clickable: true,
	minBorderMargin: 10
},
tooltip: true,
	tooltipOpts: {
	content: tipContent,
	shifts: {
		x: 0,
		y: -25
	},
	defaultTheme: false
}";

$this->js('flot/jquery.flot.min.js', 'system')
	->js('flot/jquery.flot.time.min.js', 'system')
	->js('flot/jquery.flot.pie.min.js', 'system')
	->js('flot/jquery.flot.resize.js', 'system');
?>
<header id="content-header" class="reports">
	<h2><?php echo $this->title; ?></h2>
</header><!-- / #content-header -->

<section class="main section">
	<div id="project-stats">
		<?php
		// Display status message
		$this->view('_statusmsg', 'projects')
		     ->set('error', $this->getError())
		     ->set('msg', $this->msg)
		     ->display();
		?>
		<?php if (empty($this->stats)): ?>
			<p class="error"><?php echo Lang::txt('Statistics unavailable'); ?></p>
		<?php else: ?>
			<table class="stats-wrap">
				<tr class="stats-general">
					<th scope="row" class="stats-h icon-cogs" rowspan="2"><span><?php echo Lang::txt('Overview'); ?></span></th>
					<th></th>
					<th></th>
					<th></th>
					<?php if ($this->monthly): ?>
						<th class="stats-graph"><?php echo Lang::txt('New projects'); ?></th>
					<?php endif; ?>
					<th class="stats-more"><?php echo Lang::txt('More breakdown'); ?></th>
				</tr>
				<tr>
					<td>
						<span class="stats-num"><?php echo $this->stats['general']['total']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('Total projects'); ?></span>
					</td>
					<td>
						<span class="stats-num"><?php echo $this->stats['general']['setup']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('Projects in setup'); ?></span>
					</td>
					<td>
						<span class="stats-num"><?php echo $this->stats['general']['active']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('Active projects'); ?></span>
					</td>
					<?php if ($this->monthly): ?>
						<?php
						$y         = 0;
						$xdata     = '';
						$xticks    = '';
						$yTickSize = $this->stats['general']['new'];

						foreach ($this->monthly as $month => $data):
							$xdata  .= '[' . $y . ', ' . $data['general']['new'] . ']';
							$xdata  .= (($y + 1) == count($this->monthly)) ? '' : ',';
							$xticks .= "[" . $y . ", '" . $month . "']";
							$xticks .= (($y + 1) == count($this->monthly)) ? '' : ',';
							$y++;
						endforeach;
						?>
						<td class="stats-graph">
							<div id="stat-total" class="ph"></div>
							<script type="text/javascript">
								if (!jq) {
									var jq = $;
								}
								if (jQuery()) {
									var $ = jq;

									// Detect Safari browser (interactivity doesn't work somehow)
									var safari = false;
									if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1)
									{
										safari = true;
									}
									var hover  = safari ? false : true;

									function showTooltip(x,y,contents, append)
									{
										$('<div>' +  contents + append + '</div>').css( {
											position: 'absolute',
											display: 'none',
											top: y,
											left: x,
											'border-style': 'solid',
											'border-color': '#CCC',
											'font-size': '0.8em',
											color: '#CCC',
											padding: '0 2px'
										}).appendTo("body").fadeIn(200);
									}

									function showLabels(graph, points, append)
									{
										var graphx = $(graph).offset().left;
										graphx 	   = graphx + 10;
										var graphy = $(graph).offset().top;
										graphy = graphy - 20;

										for (var k = 0; k < points.length; k++)
										{
											for (var m = 0; m < points[k].data.length; m++)
											{
												if (points[k].data[m][0] != null && points[k].data[m][1] != null)
												{
													if (k == 0)
													{
														showTooltip(graphx + points[k].xaxis.p2c(points[k].data[m][0]) - 15,
															graphy + points[k].yaxis.p2c(points[k].data[m][1]) + 10,
															points[k].data[m][1], append)
													}
													else
													{
														showTooltip(graphx + points[k].xaxis.p2c(points[k].data[m][0]) - 15,
															graphy + points[k].yaxis.p2c(points[k].data[m][1]) - 45,
															points[k].data[m][1], append)
													}
												}
											}
										}
									}

									var data       = [<?php echo $xdata; ?>];
									var xticks     = [<?php echo $xticks; ?>];
									var ph         = $('#stat-total');
									var tipContent = '%y';
									var yTickSize  = <?php echo $yTickSize; ?>;

									if (ph.length > 0)
									{
										var chart = $.plot( ph, [data], {
											<?php echo $options; ?>
										});

										// Show labels in Safari
										if (safari)
										{
											var points = chart.getData();
											showLabels(ph, points, '');
										}
									}
								}
							</script>
						</td>
					<?php endif; ?>
					<td class="stats-more">
						<ul>
							<li>
								<span class="stats-num-small"><?php echo $this->stats['general']['new']; ?></span>
								<?php echo Lang::txt('new projects this month'); ?>
							</li>
							<li>
								<span class="stats-num-small"><?php echo $this->stats['general']['public']; ?></span>
								<?php echo Lang::txt('public projects'); ?>
							</li>
							<?php if ($this->config->get('grantinfo', 0)): ?>
								<li>
									<span class="stats-num-small"><?php echo $this->stats['general']['sponsored']; ?></span>
									<?php echo Lang::txt('grant-sponsored projects'); ?>
								</li>
							<?php endif; ?>
							<?php if ($this->config->get('restricted_data', 0)): ?>
								<li>
									<span class="stats-num-small"><?php echo $this->stats['general']['sensitive']; ?></span>
									<?php echo Lang::txt('projects with sensitive data'); ?>
								</li>
							<?php endif; ?>
						</ul>
					</td>
				</tr>
			</table>

			<table class="stats-wrap">
				<tr class="stats-activity">
					<th scope="row" class="stats-h icon-bar-chart" rowspan="2"><span><?php echo Lang::txt('Activity'); ?></span></th>
					<th></th>
					<th></th>
					<th></th>
					<?php if ($this->monthly): ?>
						<th class="stats-graph"><?php echo Lang::txt('Active projects'); ?></th>
					<?php endif; ?>
					<th class="stats-more">
						<?php echo Lang::txt('Top active projects'); ?>
						<?php
						if (!$this->admin):
							echo '(' . Lang::txt('public') . ')';
						endif;
						?>
					</th>
				</tr>
				<tr>
					<td>
						<span class="stats-num"><?php echo $this->stats['activity']['total']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('total activity records'); ?></span>
					</td>
					<td>
						<span class="stats-num"><?php echo $this->stats['activity']['average']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('average activity records per project'); ?></span>
					</td>
					<td>
						<span class="stats-num"><?php echo $this->stats['activity']['usage']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('projects active in past 30 days'); ?></span>
					</td>
					<?php if ($this->monthly):?>
						<?php
						$y         = 0;
						$xdata     = '';
						$xticks    = '';
						$yTickSize = str_replace('%', '', $this->stats['activity']['usage']);

						foreach ($this->monthly as $month => $data):
							$xdata  .= '[' . $y . ', ' . str_replace('%', '', $data['activity']['usage']) . ']';
							$xdata  .= (($y + 1) == count($this->monthly)) ? '' : ',';
							$xticks .= "[" . $y . ", '" . $month . "']";
							$xticks .= (($y + 1) == count($this->monthly)) ? '' : ',';
							$y++;
						endforeach;
						?>
						<td class="stats-graph">
							<div id="stat-activity" class="ph"></div>
							<script type="text/javascript">
								if (jQuery()) {
									var $ = jq;

									var data       = [<?php echo $xdata; ?>];
									var xticks     = [<?php echo $xticks; ?>];
									var ph         = $('#stat-activity');
									var tipContent = '%y%';
									var yTickSize  = <?php echo $yTickSize; ?>;

									if (ph.length > 0)
									{
										var chart = $.plot( ph, [data], {
											<?php echo $options; ?>
										});

										// Show labels in Safari
										if (safari)
										{
											var points = chart.getData();
											showLabels(ph, points, '%');
										}
									}
								}
							</script>
						</td>
					<?php endif; ?>
					<td class="stats-more">
						<?php if (!empty($this->stats['topActiveProjects'])): ?>
							<ul>
								<?php foreach ($this->stats['topActiveProjects'] as $topProject): ?>
									<?php
									$project = new Components\Projects\Models\Project($topProject->scope_id);
									?>
									<li>
										<span class="stats-ima-small"><img src="<?php echo $project->picture('thumb'); ?>" alt="" /></span>
										<?php if (!$project->get('private')): ?>
											<a href="<?php echo Route::url('index.php?option=' . $this->option . '&task=view&alias=' . $project->get('alias')); ?>">
										<?php endif; ?>
										<?php echo $project->get('title'); ?>
										<?php if (!$project->get('private')): ?>
											</a>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php else: ?>
							<p class="noresults"><?php echo Lang::txt('Detailed information currently unavailable'); ?></p>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<table class="stats-wrap">
				<tr class="stats-team">
					<th scope="row" class="stats-h icon-group" rowspan="2"><span><?php echo Lang::txt('Team'); ?></span></th>
					<th></th>
					<th></th>
					<th></th>
					<?php if ($this->monthly): ?>
						<th class="stats-graph"><?php echo Lang::txt('New team members added'); ?></th>
					<?php endif; ?>
					<th class="stats-more">
						<?php echo Lang::txt('Top biggest team projects'); ?>
						<?php
						if (!$this->admin):
							echo '(' . Lang::txt('public') . ')';
						endif;
						?>
					</th>
				</tr>
				<tr>
					<td>
						<span class="stats-num"><?php echo $this->stats['team']['total']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('total members in all teams'); ?></span>
					</td>
					<td>
						<span class="stats-num"><?php echo $this->stats['team']['average']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('average project team size'); ?></span>
					</td>
					<td>
						<span class="stats-num"><?php echo $this->stats['team']['multi']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('projects have multi-person teams'); ?></span>
					</td>
					<?php if ($this->monthly): ?>
						<?php
						$y         = 0;
						$xdata     = '';
						$xticks    = '';
						$yTickSize = round($this->stats['team']['total']/$this->stats['general']['total'], 0);

						foreach ($this->monthly as $month => $data):
							$xdata 	.= '[' . $y . ', ' . $data['team']['new'] . ']';
							$xdata 	.= (($y + 1) == count($this->monthly)) ? '' : ',';
							$xticks .= "[" . $y . ", '" . $month . "']";
							$xticks .= (($y + 1) == count($this->monthly)) ? '' : ',';
							$y++;
						endforeach;
						?>
						<td class="stats-graph">
							<div id="stat-team" class="ph"></div>
							<script type="text/javascript">
								if (jQuery()) {
									var $ = jq;

									var data       = [<?php echo $xdata; ?>];
									var xticks     = [<?php echo $xticks; ?>];
									var ph         = $('#stat-team');
									var tipContent = '%y';
									var yTickSize  = <?php echo $yTickSize; ?>;

									if (ph.length > 0)
									{
										var chart = $.plot( ph, [data], {
											<?php echo $options; ?>
										});

										// Show labels in Safari
										if (safari)
										{
											var points = chart.getData();
											showLabels(ph, points, '');
										}
									}
								}
							</script>
						</td>
					<?php endif; ?>
					<td class="stats-more">
						<?php if (!empty($this->stats['topTeamProjects'])): ?>
							<ul>
								<?php foreach ($this->stats['topTeamProjects'] as $topProject): ?>
									<li>
										<span class="stats-ima-small"><img src="<?php echo Route::url('index.php?option=' . $this->option . '&alias=' . $topProject->alias . '&task=media'); ?>" alt="" /></span>
										<?php if (!$topProject->private): ?>
											<a href="<?php echo Route::url('index.php?option=' . $this->option . '&task=view&alias=' . $topProject->alias); ?>">
										<?php endif; ?>
										<?php echo $topProject->title . ' (' . $topProject->team . ' ' . Lang::txt('members') . ')'; ?>
										<?php if (!$topProject->private): ?>
											</a>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
							<span class="block">&nbsp;</span>
							<ul>
								<li>
									<span class="stats-num-small"><?php echo $this->stats['team']['multiusers']; ?></span>
									<?php echo Lang::txt('unique users with multiple projects'); ?>
								</li>
							</ul>
						<?php else: ?>
							<p class="noresults"><?php echo Lang::txt('Detailed information currently unavailable'); ?></p>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<table class="stats-wrap">
				<tr class="stats-files">
					<th scope="row" class="stats-h icon-file" rowspan="2">
						<span><?php
							echo Lang::txt('Files');
							if (isset($this->stats['updated'])):
								echo '*';
							endif;
						?></span>
					</th>
					<th></th>
					<th></th>
					<th></th>
					<?php if ($this->monthly): ?>
						<th class="stats-graph"><?php echo Lang::txt('Total files stored'); ?></th>
					<?php endif; ?>
					<th class="stats-more"><?php echo Lang::txt('More stats'); ?></th>
				</tr>
				<tr>
					<td>
						<span class="stats-num"><?php echo $this->stats['files']['total']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('files stored'); ?></span>
					</td>
					<td>
						<span class="stats-num"><?php echo $this->stats['files']['average']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('average files per project'); ?></span>
					</td>
					<td>
						<span class="stats-num"><?php echo $this->stats['files']['usage']; ?></span>
						<span class="stats-label"><?php echo Lang::txt('projects store files'); ?></span>
					</td>
					<?php if ($this->monthly): ?>
						<?php
						$y         = 0;
						$xdata     = '';
						$xticks    = '';
						$yTickSize = $this->stats['files']['total'];

						foreach ($this->monthly as $month => $data):
							$xdata  .= '[' . $y . ', ' . (isset($data['files']) ? $data['files']['total'] : 0) . ']';
							$xdata  .= (($y + 1) == count($this->monthly)) ? '' : ',';
							$xticks .= "[" . $y . ", '" . $month . "']";
							$xticks .= (($y + 1) == count($this->monthly)) ? '' : ',';
							$y++;
						endforeach;
						?>
						<td class="stats-graph">
							<div id="stat-files" class="ph"></div>
							<script type="text/javascript">
								if (jQuery()) {
									var $ = jq;

									var data       = [<?php echo $xdata; ?>];
									var xticks     = [<?php echo $xticks; ?>];
									var ph         = $('#stat-files');
									var tipContent = '%y';
									var yTickSize  = <?php echo $yTickSize; ?>;

									if (ph)
									{
										var chart = $.plot( ph, [data], {
											<?php echo $options; ?>
										});

										// Show labels in Safari
										if (safari)
										{
											var points = chart.getData();
											showLabels(ph, points, '');
										}
									}
								}
							</script>
						</td>
					<?php endif; ?>
					<td class="stats-more">
						<ul>
							<li>
								<span class="stats-num-small-unfloat"><?php echo $this->stats['files']['diskspace']; ?></span>
								<?php echo Lang::txt('total used disk space'); ?>
							</li>
						</ul>
					</td>
				</tr>
			</table>

			<?php if ($this->publishing): ?>
				<table class="stats-wrap">
					<tr class="stats-publications">
						<th scope="row" class="stats-h icon-success-sign" rowspan="2"><span><?php echo Lang::txt('Publications'); ?></span></th>
						<th></th>
						<th></th>
						<th></th>
						<?php if ($this->monthly): ?>
							<th class="stats-graph"><?php echo Lang::txt('Publication releases'); ?></th>
						<?php endif; ?>
						<th class="stats-more"><?php echo Lang::txt('More stats'); ?></th>
					</tr>
					<tr>
						<td>
							<span class="stats-num"><?php echo $this->stats['pub']['total']; ?></span>
							<span class="stats-label"><?php echo Lang::txt('publications started'); ?></span>
						</td>
						<td>
							<span class="stats-num"><?php echo $this->stats['pub']['average']; ?></span>
							<span class="stats-label"><?php echo Lang::txt('average publications per project'); ?></span>
						</td>
						<td>
							<span class="stats-num"><?php echo $this->stats['pub']['usage']; ?></span>
							<span class="stats-label"><?php echo Lang::txt('projects have used publications'); ?></span>
						</td>
						<?php if ($this->monthly): ?>
							<?php
							$y         = 0;
							$xdata     = '';
							$xticks    = '';
							$yTickSize = round($this->stats['pub']['total']/$this->stats['general']['total'], 0);

							foreach ($this->monthly as $month => $data):
								$xdata  .= '[' . $y . ', ' . $data['pub']['new'] . ']';
								$xdata  .= (($y + 1) == count($this->monthly)) ? '' : ',';
								$xticks .= "[" . $y . ", '" . $month . "']";
								$xticks .= (($y + 1) == count($this->monthly)) ? '' : ',';
								$y++;
							endforeach;
							?>
							<td class="stats-graph">
								<div id="stat-pub" class="ph"></div>
								<script type="text/javascript">
									if (jQuery()) {
										var $ = jq;

										var data       = [<?php echo $xdata; ?>];
										var xticks     = [<?php echo $xticks; ?>];
										var ph         = $('#stat-pub');
										var tipContent = '%y';
										var yTickSize  = <?php echo $yTickSize; ?>;

										if (ph)
										{
											var chart = $.plot( ph, [data], {
												<?php echo $options; ?>
											});

											// Show labels in Safari
											if (safari)
											{
												var points = chart.getData();
												showLabels(ph, points, '');
											}
										}
									}
								</script>
							</td>
						<?php endif; ?>
						<td class="stats-more">
							<ul>
								<li>
									<span class="stats-num-small-unfloat"><?php echo $this->stats['pub']['released']; ?></span>
									<?php echo Lang::txt('publicly released publications'); ?>
								</li>
								<li>
									<span class="stats-num-small-unfloat"><?php echo $this->stats['pub']['versions']; ?></span>
									<?php echo Lang::txt('total publication versions'); ?>
								</li>
								<li>
									<span class="stats-num-small-unfloat"><?php echo $this->stats['files']['pubspace'] ? $this->stats['files']['pubspace'] : 'N/A'; ?></span>
									<?php echo Lang::txt('allocated to published files'); ?>
								</li>
							</ul>
						</td>
					</tr>
				</table>
			<?php endif; ?>

			<?php if (isset($this->stats['updated'])): ?>
				<p><?php echo Lang::txt('*Last updated %s', $this->stats['updated']); ?></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>