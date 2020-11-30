<?php
namespace catchAdmin\permissions\model;

trait HasJobsTrait
{
    /**
     *
     * @time 2019年12月08日
     * @return mixed
     */
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'user_has_jobs', 'job_id', 'uid');
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $fields
     * @return mixed
     */
    public function getJobs()
    {
        return $this->jobs()->select();
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $jobs
     * @return mixed
     */
    public function attachJobs(array $jobs)
    {
        if (empty($jobs)) {
            return true;
        }

        sort($jobs);

        return $this->jobs()->attach($jobs);
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $jobs
     * @return mixed
     */
    public function detachJobs(array $jobs = [])
    {
        if (empty($jobs)) {
            return $this->jobs()->detach();
        }

        return $this->jobs()->detach($jobs);
    }
}
