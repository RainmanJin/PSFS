<?php

/**
 * �����ļ���
 * 
 * ���ӣ�
 * FileUtil::createDir('a/1/2/3');                    ���Խ����ļ��� ��һ��a/1/2/3�ļ���
 * FileUtil::createFile('b/1/2/3');                    ���Խ����ļ�        ��b/1/2/�ļ������潨һ��3�ļ�
 * FileUtil::createFile('b/1/2/3.exe');             ���Խ����ļ�        ��b/1/2/�ļ������潨һ��3.exe�ļ�
 * FileUtil::copyDir('b','d/e');                    ���Ը����ļ��� ����һ��d/e�ļ��У���b�ļ����µ����ݸ��ƽ�ȥ
 * FileUtil::copyFile('b/1/2/3.exe','b/b/3.exe'); ���Ը����ļ�        ����һ��b/b�ļ��У�����b/1/2�ļ����е�3.exe�ļ����ƽ�ȥ
 * FileUtil::moveDir('a/','b/c');                    �����ƶ��ļ��� ����һ��b/c�ļ���,����a�ļ����µ������ƶ���ȥ����ɾ��a�ļ���
 * FileUtil::moveFile('b/1/2/3.exe','b/d/3.exe'); �����ƶ��ļ�        ����һ��b/d�ļ��У�����b/1/2�е�3.exe�ƶ���ȥ                   
 * FileUtil::unlinkFile('b/d/3.exe');             ����ɾ���ļ�        ɾ��b/d/3.exe�ļ�
 * FileUtil::unlinkDir('d');                      ����ɾ���ļ��� ɾ��d�ļ���
 */
class FileUtil {

    /**
     * �����ļ���
     *
     * @param string $aimUrl
     * @return viod
     */
    function createDir($aimUrl) {
        $aimUrl = str_replace('', '/', $aimUrl);
        $aimDir = '';
        $arr = explode('/', $aimUrl);
        $result = true;
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!file_exists($aimDir)) {
                $result = mkdir($aimDir,0777, true);
            }
        }
        return $result;
    }

    /**
     * �����ļ�
     *
     * @param string $aimUrl 
     * @param boolean $overWrite �ò��������Ƿ񸲸�ԭ�ļ�
     * @return boolean
     */
    function createFile($aimUrl, $overWrite = false) {
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        touch($aimUrl);
        return true;
    }

    /**
     * �ƶ��ļ���
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite �ò��������Ƿ񸲸�ԭ�ļ�
     * @return boolean
     */
    function moveDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            FileUtil :: createDir($aimDir);
        }
        @ $dirHandle = opendir($oldDir);
        if (!$dirHandle) {
            return false;
        }
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                FileUtil :: moveFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                FileUtil :: moveDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        closedir($dirHandle);
        return rmdir($oldDir);
    }

    /**
     * �ƶ��ļ�
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite �ò��������Ƿ񸲸�ԭ�ļ�
     * @return boolean
     */
    function moveFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite = false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite = true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        rename($fileUrl, $aimUrl);
        return true;
    }

    /**
     * ɾ���ļ���
     *
     * @param string $aimDir
     * @return boolean
     */
    function unlinkDir($aimDir) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        if (!is_dir($aimDir)) {
            return false;
        }
        $dirHandle = opendir($aimDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($aimDir . $file)) {
                FileUtil :: unlinkFile($aimDir . $file);
            } else {
                FileUtil :: unlinkDir($aimDir . $file);
            }
        }
        closedir($dirHandle);
        return rmdir($aimDir);
    }

    /**
     * ɾ���ļ�
     *
     * @param string $aimUrl
     * @return boolean
     */
    function unlinkFile($aimUrl) {
        if (file_exists($aimUrl)) {
            unlink($aimUrl);
            return true;
        } else {
            return false;
        }
    }

    /**
     * �����ļ���
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite �ò��������Ƿ񸲸�ԭ�ļ�
     * @return boolean
     */
    function copyDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            FileUtil :: createDir($aimDir);
        }
        $dirHandle = opendir($oldDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                FileUtil :: copyFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                FileUtil :: copyDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        return closedir($dirHandle);
    }

    /**
     * �����ļ�
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite �ò��������Ƿ񸲸�ԭ�ļ�
     * @return boolean
     */
    function copyFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        copy($fileUrl, $aimUrl);
        return true;
    }

}

?>