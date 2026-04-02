<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
JIRA Tickets List
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    use App\Models\TagsModel;
    use App\Models\PropertiesModel;
    use App\Models\SubsidiariesModel;

    $tagModel = new TagsModel();
    $propertyModel = new PropertiesModel();
    $subsidiaryModel = new SubsidiariesModel();

    helper('custom_functions');
?>

<div class="d-flex justify-content-between">
    <h2>JIRA Tickets List</h2>
    <!-- <div>
        <a href="<?= site_url('jira/create') ?>" target="_blank" title="Create new JIRA ticket" class="btn btn-success btn-sm ms-auto px-2">Create New</a>
    </div> -->
</div>

<?= csrf_field() ?>

<div class="container-fluid">
    <!-- #region | SEARCH FILTER -->
    <div class="row">
        <form method="get" class="row g-3 mb-4">

            <div class="col">
                <input 
                    type="text" 
                    name="keyword" 
                    class="form-control" 
                    placeholder="Search..."
                    value="<?= esc($_GET['keyword'] ?? '') ?>"
                >
            </div>

            <div class="col-md-1">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

        </form>
    </div>
    <!-- #endregion -->

    <!-- #region | JIRA HISTORY TABLE -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subsidiary</th>
                    <th>Property</th>
                    <th>Ticket ID</th>
                    <th>Type</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($jiraTickets as $ticket):
                        $tag = $tagModel->find($ticket['tag_id']);
                        $property = $propertyModel->find($tag['property_id']);
                        $subsidiary = $subsidiaryModel->find($property['subsidiary_id']);
                ?>
                    <tr>
                        <td><?= $ticket['id'] ?></td>
                        <td><?= esc($subsidiary['name']) ?></td>
                        <td>
                            <a href="<?= $property['ref_link'] ?>" target="_blank">
                                <?= esc($property['name']) ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= Constants::JIRA_LINK . $ticket['ticket_id'] ?>" target="_blank">
                                <?= $ticket['ticket_id'] ?>
                            </a>
                        </td>
                        <td><?= Constants::JIRA_TICKET_TYPES[$ticket['type']] ?></td>
                        <td><?= $ticket['comments'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>
