<?php

class SemesterController extends PluginController
{
    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);
        if (!$GLOBALS['perm']->have_perm("root")) {
            throw new AccessDeniedException();
        }
    }

    public function select_action()
    {
        $this->semesters = Semester::getAll();
    }

    public function change_action($semester_id)
    {
        $this->semester = Semester::find($semester_id);
        if (Request::isPost()) {
            $this->nextsemester = Semester::findNext($this->semester->ende);
            $oldend = $this->semester['ende'];
            $ende = strtotime(Request::get("ende"). " " . '23:59:59');
            $this->semester['ende'] = $ende;
            $this->semester->store();

            $statement = DBManager::get()->prepare("
                UPDATE seminare
                SET duration_time = :end - seminare.start_time
                WHERE duration_time > 0 
                    AND start_time + duration_time = :oldend
            ");
            $statement->execute([
                'end' => $ende,
                'oldend' => $oldend
            ]);

            if ($this->nextsemester) {
                $old_start = $this->nextsemester['beginn'];
                $this->nextsemester['beginn'] = $ende + 1;
                $this->nextsemester->store();

                $statement = DBManager::get()->prepare("
                    UPDATE seminare
                    SET start_time = :newstart,
                        duration_time = IF(duration_time < 1, duration_time, seminare.duration_time - :newstart + :oldstart)
                    WHERE start_time = :oldstart
                ");
                $statement->execute([
                    'newstart' => $this->nextsemester['beginn'],
                    'oldstart' => $old_start
                ]);
                PageLayout::postSuccess(sprintf(_("Zeiten von Semester %s und %s wurden verändert"), $this->semester['name'], $this->nextsemester['name']));
            } else {
                PageLayout::postSuccess(sprintf(_("Zeiten von Semester %s wurden verändert"), $this->semester['name']));
            }

            $this->redirect(URLHelper::getURL("dispatch.php/admin/semester"));
            return;
        }
    }
}