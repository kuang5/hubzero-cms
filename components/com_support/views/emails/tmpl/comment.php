<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Nicholas J. Kisseberth <nkissebe@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$juri =& JURI::getInstance();
$jconfig =& JFactory::getConfig();

$sef = JRoute::_('index.php?option=' . $this->option . '&controller=' . $this->controller . '&task=ticket&id=' . $this->ticket->id);
$link = rtrim($juri->base(), DS) . DS . trim($sef, DS);

switch ($this->ticket->severity)
{
	case 'critical': $bgcolor = '#ffd3d4'; $bdcolor = '#e9bcbc'; break;
	case 'major':    $bgcolor = '#fbf1be'; $bdcolor = '#e9e1bc'; break;
	case 'minor':    $bgcolor = '#d3e3ff'; $bdcolor = '#bccbe9'; break;
	case 'trivial':  $bgcolor = '#d3f9ff'; $bdcolor = '#bce1e9'; break;

	case 'normal':
	default:
		$bgcolor = '#f1f1f1';
		$bdcolor = '#e1e1e1';
	break;
}

$this->commentor = JFactory::getUser($this->comment->created_by);
?>

--<?php echo $this->boundary . "\n"; ?>
Content-type: text/plain;charset=utf-8

<?php echo $this->delimiter . "\n"; ?>
You can reply to this message, just include your reply text above this area
Attachments (up to 2MB each) are permitted
Message from <?php echo $juri->base(); ?> / Ticket #<?php echo $this->ticket->id; ?>

<?php 
$message  = '----------------------------'."\n";
$message .= strtoupper(JText::_('TICKET')).': '.$this->ticket->id."\n";
$message .= strtoupper(JText::_('TICKET_DETAILS_SUMMARY')).': '.$this->ticket->summary."\n";
$message .= strtoupper(JText::_('TICKET_DETAILS_CREATED')).': '.$this->ticket->created."\n";
$message .= strtoupper(JText::_('TICKET_DETAILS_CREATED_BY')).': '.$this->ticket->name."\n";
$message .= strtoupper(JText::_('TICKET_FIELD_STATUS')).': '.SupportHtml::getStatus($this->ticket->status)."\n";
$message .= ($this->ticket->login) ? ' ('.$this->ticket->login.')'."\n" : "\n";
$message .= '----------------------------'."\n\n";
$message .= JText::sprintf('TICKET_EMAIL_COMMENT_POSTED',$this->ticket->id).': '.$this->comment->created_by."\n";
$message .= JText::_('TICKET_EMAIL_COMMENT_CREATED').': '.$this->comment->created."\n\n";
if ($this->comment->changelog) 
{
	foreach ($this->comment->changelog as $type => $log)
	{
		if (is_array($log) && count($log) > 0)
		{
			foreach ($log as $items)
			{
				$message .= "\n\n";
				if ($type == 'changes')
				{
					$message .= $items['field'] . ' changed from "' . $items['before'] . '" to "' . $items['before'] . '"' . "\n";
				}
				else 
				{
					$message  .= JText::_('Messaged') . ' (' . $items['role'] . ') ' . $items['name'] . ' - ' . $items['address'] . "\n";
				}
				$message .= "\n\n";
			}
		}
	}
}
$message .= $this->comment->comment;
echo $message . "\n";
?>

--<?php echo $this->boundary . "\n"; ?>
Content-type: text/html;charset=utf-8";

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" style="background-color: #fff; margin: 0; padding: 0;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!--[if gte mso 9]>
		<style type="text/css" >
		/* Outlook 2007/10 List Fix */
		.article-content ol, .article-content ul {
		  margin: 0 0 0 24px;
		  padding: 0;
		  list-style-position: inside;
		}
		</style>
		<![endif]-->
	</head>
	<body style="width: 100% !important; font-family: 'Helvetica Neue', Helvetica, Verdana, Arial, sans-serif; font-size: 12px; -webkit-text-size-adjust: none; color: #616161; line-height: 1.4em; color: #666; background: #fff; text-rendering: optimizeLegibility;" bgcolor="#ffffff">

		<!-- ====== Start Body Wrapper Table ====== -->
		<table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table" style="background-color: #ffffff; min-width: 100%;" bgcolor="#ffffff">
			<tbody>
				<tr style="border-collapse: collapse;">
					<td bgcolor="#ffffff" align="center" style="border-collapse: collapse; text-align: center;">

						<!-- ====== Start Content Wrapper Table ====== -->
						<table width="670" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
							<tbody>
								<tr style="border-collapse: collapse;">
									<td bgcolor="#ffffff" width="10" style="border-collapse: collapse;"></td>
									<td bgcolor="#ffffff" width="650" align="left" style="border-collapse: collapse; text-align: left;">

										<!-- ====== Start Header Spacer ====== -->
										<table  width="650" cellpadding="0" cellspacing="0" border="0">
											<tr style="border-collapse: collapse;">
												<td height="30" style="border-collapse: collapse;"></td>
											</tr>
										</table>
										<!-- ====== End Header Spacer ====== -->

										<!-- ====== Start Header ====== -->
										<table cellpadding="2" cellspacing="3" border="0" width="100%" style="border-collapse: collapse; border-bottom: 2px solid #e1e1e1;">
											<tbody>
												<tr>
													<td width="10%" nowrap="nowrap" align="left" valign="bottom" style="font-size: 1.4em; color: #999; padding: 0 10px 5px 0; text-align: left;">
														<?php echo $jconfig->getValue('config.sitename'); ?>
													</td>
													<td width="80%" align="left" valign="bottom" style="line-height: 1; padding: 0 0 5px 10px;">
														<span style="font-weight: bold; font-size: 0.85em; color: #666; -webkit-text-size-adjust: none;">
															<a href="<?php echo $juri->base(); ?>" style="color: #666; font-weight: bold; text-decoration: none; border: none;"><?php echo $juri->base(); ?></a>
														</span>
														<br />
														<span style="font-size: 0.85em; color: #666; -webkit-text-size-adjust: none;"><?php echo $jconfig->getValue('config.MetaDesc'); ?></span>
													</td>
													<td width="10%" nowrap="nowrap" align="right" valign="bottom" style="border-left: 1px solid #e1e1e1; font-size: 1.2em; color: #999; padding: 0 0 5px 10px; text-align: right; vertical-align: bottom;">
														Support Center
													</td>
												</tr>
											</tbody>
										</table>
										<!-- ====== End Header ====== -->

										<!-- ====== Start Header Spacer ====== -->
										<table  width="650" cellpadding="0" cellspacing="0" border="0">
											<tr style="border-collapse: collapse;">
												<td height="30" style="border-collapse: collapse;"></td>
											</tr>
										</table>
										<!-- ====== End Header Spacer ====== -->

										<table id="ticket-info" width="650" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; border: 1px solid <?php echo $bdcolor; ?>; background: <?php echo $bgcolor; ?>; font-size: 0.9em; line-height: 1.6em; background-image: -webkit-gradient(linear, 0 0, 100% 100%,
																			color-stop(.25, rgba(255, 255, 255, .075)), color-stop(.25, transparent),
																			color-stop(.5, transparent), color-stop(.5, rgba(255, 255, 255, .075)),
																			color-stop(.75, rgba(255, 255, 255, .075)), color-stop(.75, transparent),
																			to(transparent));
										background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
																		transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
																		transparent 75%, transparent);
										background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
																		transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
																		transparent 75%, transparent);
										background-image: -ms-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
																		transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
																		transparent 75%, transparent);
										background-image: -o-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
																		transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
																		transparent 75%, transparent);
										background-image: linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
																		transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
																		transparent 75%, transparent);
																		-webkit-background-size: 30px 30px;
																		-moz-background-size: 30px 30px;
																		background-size: 30px 30px;">
											<thead>
												<tr>
													<th colspan="2" style="font-weight: normal; border-bottom: 1px solid <?php echo $bdcolor; ?>; padding: 8px; text-align: left" align="left">
														<?php echo $this->escape($this->ticket->summary); ?>
													</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td id="ticket-number" style="padding: 8px; font-size: 2.5em; font-weight: bold; text-align: center; padding: 8px 30px;" align="center">
														#<?php echo $this->ticket->id; ?>
													</td>
													<td width="100%" style="padding: 8px;">
														<table style="border-collapse: collapse; font-size: 0.9em;" cellpadding="0" cellspacing="0" border="0">
															<tbody>
																<tr>
																	<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Created:</th>
																	<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->ticket->created; ?></td>
																</tr>
																<tr>
																	<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Creator:</th>
																	<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->ticket->name ? $this->ticket->name : 'Unknown'; ?> <?php echo $this->ticket->login ? '(' . $this->ticket->login . ')' : ''; ?></td>
																</tr>
																<tr>
																	<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Severity:</th>
																	<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->ticket->severity; ?></td>
																</tr>
																<tr>
																	<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Status:</th>
																	<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo SupportHtml::getStatus($this->ticket->status); ?></td>
																</tr>
																<tr>
																	<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right">Link:</th>
																	<td style="text-align: left; padding: 0 0.5em;" align="left"><a href="<?php echo $link; ?>"><?php echo $link; ?></a></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>

										<table width="650" id="ticket-comments" style="border-collapse: collapse; margin: 2em 0 0 0; padding: 0" cellpadding="0" cellspacing="0" border="0">
											<tbody>
												<tr>
													<th style="text-align: left;" align="left"><?php echo $this->commentor->get('name'); ?> (<?php echo $this->commentor->get('username'); ?>)</th>
													<th class="timestamp" style="color: #999; text-align: right;" align="right"><?php echo $this->comment->created; ?></th>
												</tr>
												<tr>
													<td colspan="2" style="padding: 0 2em;">
													<?php
														if ($this->comment->changelog && count($this->comment->changelog) >  0) 
														{
													?>
														<table id="ticket-updates" width="100%" style="border-collapse: collapse; border-top: 1px solid #e1e1e1; margin: 1em 0 2em 0; color: #616161;" cellpadding="0" cellspacing="0" border="0">
															<tbody>
															<?php
															//if (substr($this->comment->changelog, 0, 1) == '{')
															//{
																//$logs = json_decode($this->comment->changelog, true);
																$clog = '';
																foreach ($this->comment->changelog as $type => $log)
																{
																	if (is_array($log) && count($log) > 0)
																	{
																		foreach ($log as $items)
																		{
																			$clog .= '<tr class="' . $type . '">';
																			if ($type == 'changes')
																			{
																				$clog .= '<td width="100%" style="border-bottom: 1px solid #e1e1e1; text-align: left; padding: 0.4em 0.8em;" align="left"><span style="color: #4e7ac7;">' . $items['field'] . '</span> changed from "<b style="color: #333;">' . $items['before'] . '</b>" to "<b style="color: #333;">' . $items['after'] . '</b>"</td>';
																			}
																			else 
																			{
																				$clog .= '<td width="100%" style="border-bottom: 1px solid #e1e1e1; text-align: left; padding: 0.4em 0.8em;" align="left"><span style="color: #4e7ac7;">' . JText::_('Messaged') . '</span> (' . $items['role'] . ') ' . $items['name'] . ' - ' . $items['address'] . '</td>';
																			}
																			$clog .= '</tr>';
																		}
																	}
																}
																echo $clog;
															//}
													?>
															</tbody>
														</table>
													<?php
														}
														if (!strstr($this->comment->comment, '</p>') && !strstr($this->comment->comment, '<pre class="wiki">')) 
														{
															$this->comment->comment = str_replace("<br />", '', $this->comment->comment);
															$this->comment->comment = $this->escape($this->comment->comment);
															$this->comment->comment = nl2br($this->comment->comment);
															$this->comment->comment = str_replace("\t", ' &nbsp; &nbsp;', $this->comment->comment);
															$this->comment->comment = preg_replace('/  /', ' &nbsp;', $this->comment->comment);
														}
														$this->comment->comment = $this->attach->parse($this->comment->comment);
													?>
														<p style="line-height: 1.6em; margin: 1em 0; padding: 0; text-align: left;"><?php echo $this->comment->comment; ?></p>
													</td>
												</tr>
											</tbody>
										</table>

										<!-- ====== Start Footer Spacer ====== -->
										<table width="650" cellpadding="0" cellspacing="0" border="0">
											<tr style="border-collapse: collapse;">
												<td height="30" style="border-collapse: collapse;"></td>
											</tr>
										</table>
										<!-- ====== End Footer Spacer ====== -->

										<!-- ====== Start Header ====== -->
										<table width="650" cellpadding="2" cellspacing="3" border="0" style="border-collapse: collapse; border-top: 2px solid #e1e1e1;">
											<tbody>
												<tr>
													<td align="left" valign="bottom" style="line-height: 1; padding: 5px 0 0 0; ">
														<span style="font-size: 0.85em; color: #666; -webkit-text-size-adjust: none;"><?php echo $jconfig->getValue('config.sitename'); ?> sent this email because you were added to the list of recipients on <a href="<?php echo $juri->base(); ?>"><?php echo $juri->base(); ?></a>. Visit our <a href="<?php echo $juri->base(); ?>/legal/privacy">Privacy Policy</a> and <a href="<?php echo $juri->base(); ?>/support">Support Center</a> if you have any questions.</span>
													</td>
												</tr>
											</tbody>
										</table>
										<!-- ====== End Header ====== -->

										<!-- ====== Start Footer Spacer ====== -->
										<table  width="650" cellpadding="0" cellspacing="0" border="0">
											<tr style="border-collapse: collapse;">
												<td height="30" style="border-collapse: collapse;"></td>
											</tr>
										</table>
										<!-- ====== End Footer Spacer ====== -->

									</td>
									<td bgcolor="#ffffff" width="10" style="border-collapse: collapse;"></td>
								</tr>
							</tbody>
						</table>
						<!-- ====== End Content Wrapper Table ====== -->
					</td>
				</tr>
			</tbody>
		</table>
		<!-- ====== End Body Wrapper Table ====== -->
		<style type="text/css">
		body { width: 100% !important; font-family: 'Helvetica Neue', Helvetica, Verdana, Arial, sans-serif !important; background-color: #ffffff !important; margin: 0 !important; padding: 0 !important; }
		img { outline: none !important; text-decoration: none !important; display: block !important; }
		@media only screen and (min-device-width: 481px) { body { -webkit-text-size-adjust: 140% !important; } }
		</style>
	</body>
</html>

--<?php echo $this->boundary; ?>--