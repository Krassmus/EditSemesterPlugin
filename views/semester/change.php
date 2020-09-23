<form action="<?= PluginEngine::getLink($plugin, [], "semester/change/".$semester->id) ?>"
      class="default"
      method="post">
    <div>
        <?= _("Altes Ende des Semesters:") ?>
        <?= strftime('%x', $semester->ende) ?>
    </div>

    <label>
        <?= _("Ende des Semesters") ?>
        <input type="text" name="ende" value="<?= strftime('%x', $semester->ende) ?>" data-date-picker>
    </label>

    <div data-dialog-button>
        <?= \Studip\Button::create(_("Speichern"), "save", ['data-confirm' => _("Wirklich das Ende des Semesters verändern?")]) ?>
    </div>
</form>
