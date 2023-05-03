<?php

namespace W360\SecureData\Contracts;

interface SecureDataEncrypted
{

    /**
     * Get secure decrypt attributes
     *
     * @return array
     * @throws Exception
     */
    public function getSecureDecryptAttributes();

    /**
     * Get secure encrypt attributes
     *
     * @return array
     * @throws Exception
     */
    public function getSecureEncryptAttributes($values = []);

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function newBaseQueryBuilder();

}