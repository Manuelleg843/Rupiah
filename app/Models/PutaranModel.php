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
            ->select(['putaran.id_komponen', 'komponen_7.komponen', 'nilai', 'periode', 'putaran'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->where('periode', $periode)
            ->where('putaran', $putaran)
            ->orderBy('id_komponen')
            ->orderBy('periode');

        return $builder->get()->getResult();
    }

    public function getPutaranTerakhir()
    {
        // get current year 
        $currentYear = date("Y");
        $builder = $this->db->query('SELECT DISTINCT putaran FROM Putaran where tahun=' . $currentYear);
        return $builder->getResultArray();
    }

    // Fungsi mendapatkan data final (putaran terakhir)
    public function getDataFinal($idPDRB, $kota, $periode)
    {
        $tahun = substr($periode, 0, 4);
        $kuartal = substr($periode, 5, 1);
        if ($kuartal == '') $kuartal = 5;
        $putaran = $this->getFinalPutaran($tahun, $kuartal, $kota);
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['putaran.id_komponen', 'komponen_7.komponen', 'nilai', 'periode', 'id_wilayah', 'id_pdrb',])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->where('periode', $periode)
            ->where('putaran', $putaran)
            ->orderBy('periode')
            ->orderBy('id_komponen');

        return $builder->get()->getResult();
    }

    // Fungsi mendapatkan putaran terakhir
    public function getFinalPutaran($tahun, $id_kuartal, $id_wilayah)
    {
        $query = $this->db->table('putaran')
            ->where('tahun', $tahun)
            ->where('id_kuartal', $id_kuartal)
            ->where('id_wilayah', $id_wilayah)
            ->selectMax('putaran')
            ->get();

        if ($query->getResult()) {
            $result = $query->getRow();
            $putaran = $result->putaran;
            return $putaran;
        }
    }

    public function batchUpdate($updateBatchData)
    {
        $builder = $this->db->table('putaran');
        $builder->updateBatch($updateBatchData, ['periode', 'id_wilayah', 'id_pdrb', 'putaran', 'id_komponen']);
    }

    public function getPutaranTerakhirPeriode($kota, $periode)
    {
        $builder =  $this->db->query("SELECT MAX(putaran) AS max_putaran FROM Putaran WHERE periode = '$periode' AND id_wilayah = '$kota'");
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

    public function getObject()
    {
        $builder = $this->db->table('putaran')
            ->select();
        return $builder->get()->getResultObject();
    }

    public function getDataKomponen($kota, $jenisPDRB, $jeniskomponen) //cara buat otomatis satker
    {
        $PutaranModel = new PutaranModel();
        // Untuk Cari Kuartal Sekarang
        $Query = "SELECT tahun, id_kuartal, putaran FROM putaran WHERE id_wilayah = '$kota' AND (tahun = (SELECT MAX(tahun) FROM putaran))
            AND (id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE id_wilayah = '$kota' AND tahun = (SELECT MAX(tahun) FROM putaran)))
            AND id_wilayah = '$kota'
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_wilayah = '$kota' AND id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran))))";
        $Subquery = $PutaranModel->db->query($Query)->getResult();
        // Untuk Yang Ada Tahunan
        $QueryKhususYear = "SELECT tahun, id_kuartal, putaran FROM putaran WHERE (tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4 AND id_wilayah = '$kota'))
            AND id_kuartal = 4
            AND id_wilayah = '$kota'
            AND (putaran = (SELECT MAX(putaran) FROM putaran WHERE id_kuartal = (SELECT MAX(id_kuartal) FROM putaran WHERE tahun = (SELECT MAX(tahun) FROM putaran WHERE id_kuartal = 4 AND id_wilayah = '$kota'))))";
        $SubqueryKhususYear = $PutaranModel->db->query($QueryKhususYear)->getResult();
        if ($Subquery[0]->id_kuartal <= 4) {
            $maxSubquery = $Subquery;
        } else {
            $maxSubquery = $SubqueryKhususYear;
        }

        $builder = $this->table('putaran')
            ->select()
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)

            ->where('tahun', $maxSubquery[0]->tahun)
            ->where('id_kuartal', $maxSubquery[0]->id_kuartal)
            ->where('putaran', $maxSubquery[0]->putaran);
        return $builder->get()->getResult();
    }

    public function getDataKomponenPeriode($kota, $jenisPDRB, $jeniskomponen, $periode) //cara buat otomatis satker
    {
        $putaranMax = $this->getPutaranTerakhirPeriode($kota, $periode);
        $builder = $this->table('putaran')
            ->select()
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)
            ->where('periode', $periode)
            ->where('putaran', $putaranMax);
        return $builder->get()->getResult();
    }

    public function get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode) //cara buat otomatis satker
    {
        $putaranMax = $this->getPutaranTerakhirPeriode($kota, $periode);
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select()
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('periode', $periode)
            ->where('putaran', $putaranMax)
            ->orderBy('putaran.id_komponen');
        return $builder->get()->getResult();
    }

    // Fungsi untuk mengecek wilayah mana saja yang sudah upload putaran saat ini (halaman monitoring)
    public function sudahUpload($tahun, $id_kuartal, $putaran, $wilayah)
    {
        $subBuilder = $this->db->table('putaran')
            ->select('id_wilayah')
            ->where('tahun', $tahun)
            ->where('id_kuartal', $id_kuartal)
            ->where('putaran', $putaran)
            ->where('id_pdrb', 1)
            ->distinct()
            ->orderBy('id_wilayah', 'asc');
        $wilayahSudah = array_map('current', $subBuilder->get()->getResultArray());

        $builder = $this->db->table('putaran')
            ->select('id_wilayah, uploaded_at, uploaded_by');
        if (!empty($wilayahSudah)) {
            $builder->whereIn('id_wilayah', $wilayahSudah);
        }
        $builder->groupBy(['id_wilayah', 'uploaded_by'])
            ->orderBy('id_wilayah', 'asc');
        $arraySudah = $builder->get()->getResultArray();

        for ($i = 0; $i < count($wilayah); $i++) {
            if (in_array($wilayah[$i], $wilayahSudah)) {
                $statusSudah[] = 1;
                $uploadAtSudah[] = current($arraySudah)['uploaded_at'];
                $uploadBySudah[] = current($arraySudah)['uploaded_by'];
                next($arraySudah);
            } else {
                $statusSudah[] = 0;
                $uploadAtSudah[] = "";
                $uploadBySudah[] = "";
            }
        }

        return [
            'status' => $statusSudah,
            'upload_at' => $uploadAtSudah,
            'upload_by' => $uploadBySudah
        ];
    }

    public function getDataFinalMod($idPDRB, $kota, $periode)
    {

        $tahun = substr($periode, 0, 4);
        $kuartal = substr($periode, 5, 1);
        if ($kuartal == '') $kuartal = 5;
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['putaran.id_komponen', 'komponen_7.komponen', 'nilai', 'periode', 'id_wilayah', 'id_pdrb', 'putaran'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->where('periode', $periode)
            ->where('putaran', '2')
            ->orderBy('periode')
            ->orderBy('id_komponen');

        return $builder->get()->getResultObject();
    }

    public function getPutaranTerakhirbyWilayah($wilayah, $periode)
    {
        $builder =  $this->db->query("SELECT MAX(putaran) AS max_putaran FROM Putaran WHERE periode = '$periode' AND id_wilayah = '$wilayah'");
        $row = $builder->getRow();
        $maxPutaran = $row->max_putaran;
        return $maxPutaran;
    }

    public function getAllPutaranByPeriode($periode)
    {
        $builder = $this->db->query("SELECT DISTINCT putaran FROM putaran WHERE periode = '$periode'");
        return $builder->getResultArray();
    }

    public function getAllPutaran($wilayah, $periode)
    {
        $builder = $this->db->table('putaran')
            ->select('putaran')
            ->distinct()
            ->where('periode', $periode)
            ->where('id_wilayah', $wilayah);
        return $builder->get()->getResult();
    }
}
