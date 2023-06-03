<!-- Why Virtually Stage? -->
<div class="container my-5">
    <div class="fs-2 fw-bold text-center">
        Why Virtually Stage?
    </div>
    <div class="border rounded-2 p-5 mt-4 shadow">
        <?= html_entity_decode(htmlspecialchars_decode($setting['why_virtually_stage'])) ?>
    </div>
</div>

<!-- Why Stuccco Virtual Staging? -->
<div class="container my-5">
    <div class="fs-2 fw-bold text-center">
        Why Stuccco Virtual Staging?
    </div>
    <div class="border rounded-2 p-5 mt-4 shadow">
        <?= html_entity_decode(htmlspecialchars_decode($setting['why_stageofvisualization'])) ?>
    </div>
</div>

<!-- Frequently Asked Questions-->
<div class="container-fluid">
    <div class="container py-5">
        <div class="fs-2 fw-bold text-center mb-4">
            Frequently Asked Questions
        </div>
        <div class="accordion" id="accordionPanelsStayOpenExample">
            <?php $asked_question = json_decode($setting['asked_question'], true); ?>
            <?php foreach ($asked_question as $id => $it) { ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse<?= $id ?>" aria-expanded="true" aria-controls="panelsStayOpen-collapse<?= $id ?>">
                            <?= $it['asked'] ?>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapse<?= $id ?>" class="accordion-collapse collapse show">
                        <div class="accordion-body fs-5">
                            <?= $it['question'] ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="my-5 d-flex flex-column align-items-center">
            <a href="<?= site_url(LINK_ORDER) ?>" class="btn btn-danger btn-lg mt-2 px-4 text-white">Place order</a>
            <div class="mt-2">
                Questions? Call
                <a href="tel: 0987654321"><span class="link-color">1-833-788-2226</span></a>
            </div>
        </div>
    </div>
</div>