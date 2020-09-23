<table class="default">
    <caption>
        <?= _("Semester") ?>
    </caption>
    <thead>
        <tr>
            <th><?= _("Name") ?></th>
            <th><?= _("Zeitraum") ?></th>
            <th class="actions"><?= _("Aktion") ?></th>
        </tr>
    </thead>
    <tbody>
        <? foreach (array_reverse($semesters) as $semester) : ?>
        <tr>
            <td><?= htmlReady($semester['name']) ?></td>
            <td>
                <?= strftime('%x', $semester->beginn) ?>
                -
                <?= strftime('%x', $semester->ende) ?>
            </td>
            <td class="actions">
                <a href="<?= PluginEngine::getLink($plugin, [], "semester/change/".$semester->id) ?>" data-dialog>
                    <?= Icon::create("edit", "clickable")->asImg(20) ?>
                </a>
            </td>
        </tr>
        <? endforeach ?>
    </tbody>
</table>