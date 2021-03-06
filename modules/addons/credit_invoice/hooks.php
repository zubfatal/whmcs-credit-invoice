<?php

use \WHMCS\Billing\Invoice;
use \WHMCS\Billing\Invoice\Item;

defined('WHMCS') || exit;

require_once __DIR__ . '/functions.php';

add_hook('AdminInvoicesControlsOutput', 1, function($vars) {
	ob_start(); ?>

	<?php if ($creditId = invoice_is_credited($vars['invoiceid'])[1]): ?>

		<a href="invoices.php?action=edit&id=<?= $creditId ?>" class="button btn btn-default">Credited in <?= $creditId ?></a>

	<?php elseif ($originalId = invoice_is_creditnote($vars['invoiceid'])[1]): ?>

		<a href="invoices.php?action=edit&id=<?= $originalId ?>" class="button btn btn-default">Credit invoice of <?= $originalId ?></a>

	<?php else: ?>

		<form method="POST" action="addonmodules.php?module=credit_invoice" name="credit_invoice_actions" style="display:inline;margin-top: 5px;">
			<input type="hidden" name="invoice" value="<?= $vars['invoiceid'] ?>">
			<button type="submit" name="action" value="credit"
			class="button btn btn-default"
			data-toggle="tooltip"
			data-placement="left"
			data-original-title="Click to copy invoice to a credit note, with reversed line items.">Credit invoice</button>
		</form>

	<?php endif ?>

	<?php echo ob_get_clean();
});

add_hook('ClientAreaPageViewInvoice', 1, function($vars) {

	$data = [
		'notes' => credit_invoice_replace_notes($vars['notes'], true),
		'pagetitle' => credit_invoice_replace_pagetitle($vars['invoiceid'], $vars['pagetitle']),
	];
	
	return $data;
});

