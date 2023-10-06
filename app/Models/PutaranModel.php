<?php

namespace App\Models;

use CodeIgniter\Model;

class PutaranModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'putaran';
    protected $primaryKey       = 'id_putaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'periode',
        'id_kuartal',
        'id_komponen',
        'id_wilayah',
        'id_pdrb',
        'tahun',
        'putaran',
        'nilai',
        'uploaded_by',

    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // get all data 
    public function getAll()
    {
        $builder = $this->db->table('putaran')->select();
        return $builder->get()->getResult();
    }
    // get jenis pdrb
    public function getJenisPDRB($jenisPDRB)
    {
        $query = $this->db->table('jenis_pdrb')
            ->where('id_pdrb', $jenisPDRB);
        return $query->get()->getResult();
    }

    // get wilayah
    public function getWilayah()
    {
        $query = $this->db->table('wilayah')
            ->orderBy('id_wilayah');
        return $query->get()->getResult();
    }

    public function getDataHistory($idPDRB, $kota, $putaran, $periode)
    {

        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['putaran.id_komponen', 'komponen_7.komponen', 'nilai', 'periode'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->whereIn('periode', $periode)
            ->orderBy('id_komponen')
            ->orderBy('periode');

        $putaran == 'null' ? '' : $builder->where('putaran', $putaran);

        return $builder->get()->getResult();
    }

    public function getPutaranTerakhir()
    {
        // get current year 
        $currentYear = date("Y");
        $builder = $this->db->query('SELECT DISTINCT putaran FROM Putaran where tahun=' . $currentYear);
        return $builder->getResultArray();
    }

    public function getPutaranTerakhirPeriode($periode)
    {
        $builder =  $this->db->query("SELECT MAX(putaran) AS max_putaran FROM Putaran WHERE periode = '$periode'");
        $row = $builder->getRow();
        $maxPutaran = $row->max_putaran;
        return $maxPutaran;
    }

    public function getAllPeriode()
    {
        $builder = $this->db->table('putaran')
            ->select()
            ->distinct('periode')
            ->orderBy('tahun', 'desc');
        return $builder->get()->getResult();
    }

    public function getTabel1($periode, $komponen)
    {
        $builder = $this->db->table('putaran')
            ->select()
            ->where('id_pdrb', "1")
            ->whereIn('id_komponen', $komponen)
            ->orderBy('periode', 'ASC')
            ->orderBy('id_komponen', 'ASC')
            ->orderBy('id_wilayah', 'ASC');
        foreach ($periode as $value) {
            $putaran =  $this->getPutaranTerakhirPeriode($value);
            $builder->where('periode', $value)->where('putaran', $putaran);
        }
        return $builder->get()->getResult();
    }

    public function getDataKomponen($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Yang Ada Tahunan
        $QueryKhususYear = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4))
            AND id_kuartal = 4
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4))))';
        $SubqueryKhususYear = $PutaranModel->db->query($QueryKhususYear)->getResult();
        // Untuk Kuartal
        $QueryKhususKuartal = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $SubqueryKhususKuartal = $PutaranModel->db->query($QueryKhususKuartal)->getResult();
        if ($Subquery[0]->id_kuartal >= 5) {
            $maxSubquery = $SubqueryKhususYear;
        } else {
            $maxSubquery = $SubqueryKhususKuartal;
        }

        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenMinusYear($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Yang Ada Tahunan
        $QueryKhususYear = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)-1)
            AND id_kuartal = 4
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)-1)))';
        $SubqueryKhususYear = $PutaranModel->db->query($QueryKhususYear)->getResult();
        // Untuk Kuartal
        $QueryKhususKuartal = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran) - 1)
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) - 1))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) - 1)))';
        $SubqueryKhususKuartal = $PutaranModel->db->query($QueryKhususKuartal)->getResult();
        if ($Subquery[0]->id_kuartal >= 5) {
            $maxSubquery = $SubqueryKhususYear;
        } else {
            $maxSubquery = $SubqueryKhususKuartal;
        }
        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenMinusKuartal($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Yang Ada Tahunan
        $QueryKhususYear = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)
            AND id_kuartal = 3
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)))';
        $SubqueryKhususYear = $PutaranModel->db->query($QueryKhususYear)->getResult();
        // Untuk Q2, Q3, Q4
        $QueryNormal = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran)))-1)
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))-1))';
        $SubqueryNormal = $PutaranModel->db->query($QueryNormal)->getResult();
        if ($Subquery[0]->id_kuartal >= 5) {
            $maxSubquery = $SubqueryKhususYear;
        } else {
            $maxSubquery = $SubqueryNormal;
        }

        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenMinus2Kuartal($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Yang Ada Tahunan
        $QueryKhususYear = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)
            AND id_kuartal = 2
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)))';
        $SubqueryKhususYear = $PutaranModel->db->query($QueryKhususYear)->getResult();
        // Untuk Q3 dan Q4
        $QueryNormal = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran)))-2)
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))-2))';
        $SubqueryNormal = $PutaranModel->db->query($QueryNormal)->getResult();
        if ($Subquery[0]->id_kuartal >= 5) {
            $maxSubquery = $SubqueryKhususYear;
        } else {
            $maxSubquery = $SubqueryNormal;
        }

        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenMinus3Kuartal($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Yang Ada Tahunan
        $QueryKhususYear = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) -1
            AND id_kuartal = 1
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 1)-1))';
        $SubqueryKhususYear = $PutaranModel->db->query($QueryKhususYear)->getResult();
        // Untuk Q4
        $QueryNormal = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran)))-3)
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))-3))';
        $SubqueryNormal = $PutaranModel->db->query($QueryNormal)->getResult();
        if ($Subquery[0]->id_kuartal >= 5) {
            $maxSubquery = $SubqueryKhususYear;
        } else {
            $maxSubquery = $SubqueryNormal;
        }


        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenMinusYearKuartal($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Q2
        $QueryKhususQ2 = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) -1
            AND id_kuartal = 1
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)-1))';
        $SubqueryKhususQ2 = $PutaranModel->db->query($QueryKhususQ2)->getResult();
        // Untuk Q3
        $QueryKhususQ3 = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) -1 
            AND id_kuartal = 2 
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)-1))';
        $SubqueryKhususQ3 = $PutaranModel->db->query($QueryKhususQ3)->getResult();
        // Untuk Q4
        $QueryKhususQ4 = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) -1 
            AND id_kuartal = 3 
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)-1))';
        $SubqueryKhususQ4 = $PutaranModel->db->query($QueryKhususQ4)->getResult();
        if ($Subquery[0]->id_kuartal <= 2) {
            $maxSubquery = $SubqueryKhususQ2;
        } elseif ($Subquery[0]->id_kuartal <= 3) {
            $maxSubquery = $SubqueryKhususQ3;
        } elseif ($Subquery[0]->id_kuartal >= 4) {
            $maxSubquery = $SubqueryKhususQ4;
        }
        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenMinusYear2Kuartal($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Q3
        $QueryKhususQ3 = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) -1 
            AND id_kuartal = 1 
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)-1))';
        $SubqueryKhususQ3 = $PutaranModel->db->query($QueryKhususQ3)->getResult();
        // Untuk Q4
        $QueryKhususQ4 = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) -1 
            AND id_kuartal = 2 
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)-1))';
        $SubqueryKhususQ4 = $PutaranModel->db->query($QueryKhususQ4)->getResult();
        if ($Subquery[0]->id_kuartal <= 3) {
            $maxSubquery = $SubqueryKhususQ3;
        } elseif ($Subquery[0]->id_kuartal >= 4) {
            $maxSubquery = $SubqueryKhususQ4;
        }
        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenMinusYear3Kuartal($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Q4
        $QueryKhususQ4 = 'SELECT * FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran) -1 
            AND id_kuartal = 1 
            AND putaran = (SELECT MAX(putaran) FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4)-1))';
        $SubqueryKhususQ4 = $PutaranModel->db->query($QueryKhususQ4)->getResult();
        $maxSubquery = $SubqueryKhususQ4;

        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenDiskrepansi($kota, $jenisPDRB, $jeniskomponen) //Jangan jadikan satker otomatis
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        $maxSubquery = $Subquery;
        // Untuk Yang Ada Tahunan
        $QueryKhususYear = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4))
            AND id_kuartal = 4
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4))))';
        $SubqueryKhususYear = $PutaranModel->db->query($QueryKhususYear)->getResult();
        // Untuk Kuartal
        $QueryKhususKuartal = 'SELECT * FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran)))
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))';
        $SubqueryKhususKuartal = $PutaranModel->db->query($QueryKhususKuartal)->getResult();
        if ($Subquery[0]->id_kuartal >= 5) {
            $maxSubquery = $SubqueryKhususYear;
        } else {
            $maxSubquery = $SubqueryKhususKuartal;
        }

        $builder = $this->table('putaran')
            ->select(['putaran.id_komponen', 'tahun', 'id_kuartal', 'id_wilayah', 'id_pdrb', 'uploaded_at', 'putaran', 'nilai'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode) //cara buat otomatis satker
    {
        $putaranMax = $this->getPutaranTerakhirPeriode($periode);
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select()
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('periode', $periode)
            ->where('putaran', $putaranMax)
            ->orderBy('id_komponen');
        return $builder->get()->getResult();
    }
}
