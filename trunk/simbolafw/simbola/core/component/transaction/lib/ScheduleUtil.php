<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace simbola\core\component\transaction\lib;

/**
 * Description of ScheduleUtil
 *
 * @author faraj
 */
class ScheduleUtil {
    
    /**
     * This method will run the scheduler for the transaction schedules
     */
    function runScheduler() {
        $schedules = \application\system\model\transaction\Schedule::find('all', array('_state' => 'ready'));
        $currTime = time();
        foreach ($schedules as $schedule) {
            if($schedule->valid_from->getTimestamp() <= $currTime){                
                if($schedule->valid_till->getTimestamp() > $currTime){                        
                    if($schedule->next_execute->getTimestamp() <= $currTime){
                        $this->createJobFor($schedule);       
                        if($schedule->last_execute == NULL){
                            $schedule->last_execute = new \DateTime();
                        }
                        $schedule->last_execute->setTimestamp($currTime);
                        $schedule->execute_count++;
                        $nextExecute = $currTime + $schedule->interval;
                        if($nextExecute < $schedule->valid_till->getTimestamp()){
                            $schedule->next_execute->setTimestamp($nextExecute);                        
                        }else{
                            $schedule->next_execute = null;
                            $schedule->state('complete');
                        }                    
                    }                
                }else{
                    $schedule->next_execute = null;
                    $schedule->state('complete');
                }
                $schedule->save();
            }
        }
    }
    
    /**
     * 
     * @param \application\system\model\transaction\Schedule $schedule
     */
    private function createJobFor($schedule) {
        //Job
        $job = new \application\system\model\transaction\Job();
        $job->user_id = $schedule->user_id;
        $job->queue_id = $schedule->queue_id;
        $job->priority = $schedule->priority;
        $job->type = $schedule->type;
        $job->content = $schedule->content;
        $job->save();
        //schedulejob
        $scheduleJob = new \application\system\model\transaction\ScheduleJob();
        $scheduleJob->schedule_id = $schedule->id;
        $scheduleJob->job_id = $job->id;
        $scheduleJob->save();
    }
}
