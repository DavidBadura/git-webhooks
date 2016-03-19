<?php

namespace DavidBadura\GitWebhooks\Provider;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractProvider
{
    /**
     * @param Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $body = $request->getContent();

        return json_decode($body, true);
    }

    /**
     * @param array $data
     * @return Commit[]
     */
    protected function createCommits(array $data)
    {
        $result = [];

        foreach ($data as $row) {
            $result[] = $this->createCommit($row);
        }

        return $result;
    }

    abstract protected function createCommit(array $data);
}