-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 07, 2023 at 03:23 PM
-- Server version: 5.6.51
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Table structure for table `Revisi`
--

CREATE TABLE `Revisi`(
    `id` int NOT NULL,
    `id_kuartal` int NOT NULL,
    `id_komponen` int NOT NULL,
    `id_wilayah` int NOT NULL,
    `is_provinsi` int NOT NULL,
    `tahun_rilis` int NOT NULL,
    'nilai' FLOAT NOT NULL,
)

--
-- Table structure for table `Putaran`
--

CREATE TABLE `Putaran`(
    `id` int NOT NULL,
    `id_kuartal` int NOT NULL,
    `id_komponen` int NOT NULL,
    `id_wilayah` int NOT NULL,
    `is_provinsi` int NOT NULL,
    `tahun_rilis` int NOT NULL,
    `putaran` int NOT NULL,
    'nilai' FLOAT NOT NULL,
)

--
-- Table structure for table `Kuartal`
--

CREATE TABLE `Kuartal`(
    `id` int NOT NULL,
    `kuartal` char(2) NOT NULL
)

--
-- Table structure for table `Komponen`
--

CREATE TABLE `Komponen`(
    `id` int NOT NULL,
    `komponen` varchar(255) NOT NULL
)




--
-- Database: `jakartab_ckponline`
--

-- --------------------------------------------------------

--
-- Table structure for table `master_pegawai`
--

ALTER TABLE `master_pegawai`
  ADD PRIMARY KEY (`niplama`);
COMMIT;

CREATE TABLE `Pegawai` (
  `niplama` varchar(9) NOT NULL,
  `username` varchar(20) NOT NULL,
  `nipbaru` varchar(25) NOT NULL,
  `gelar_depan` varchar(20) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `gelar_belakang` varchar(100) NOT NULL,
  `email` varchar(40) NOT NULL,
  `id_gol` char(2) NOT NULL,
  `id_org` char(5) NOT NULL,
  `id_orgkabid` char(5) NOT NULL,
  `id_satker` char(4) NOT NULL,
  `id_fungsional` varchar(100) NOT NULL,
  `levels` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for table `master_pegawai`
--

INSERT INTO `Pegawai` (`niplama`, `username`, `nipbaru`, `gelar_depan`, `nama`, `gelar_belakang`, `email`, `id_gol`, `id_org`, `id_orgkabid`, `id_satker`, `id_fungsional`, `levels`) VALUES
('320007100', '', '197711192002122005', '', 'Heryanah', 'S.IP., MA., M.S.E', 'heryanah@bps.go.id', '41', '92310', '92300', '3100', 'Statstisi Ahli Madya', 0),
('340011622', '', '199603241987032003', '', 'Ratna Puniati Purba', 'S.Sos', 'ratnapurba@bps.go.id', '34', '92430', '', '3100', 'Statistisi Ahli Muda', 0),
('340011640', '', '196705111987031002', '', 'Bambang Supriono', 'S.Si.,M.M.', 'aprie@bps.go.id', '42', '92300', '', '3100', 'Statistisi Ahli Madya', 0),
('340011685', '', '196410141987021001', '', 'Suhartono ', 'S.Si, SE, MM', 'suhartono2@bps.go.id', '42', '92800', '', '3175', '', 0),
('340011700', '', '196511191987022001', '', 'Pudji Pangastuti', 'SST, M.AP.', 'pudjip@bps.go.id', '42', '92800', '', '3174', '', 0),
('340011838', '', '196603111988022001', '', 'Rini Apsari', 'S.Si, M.Si', 'riniapsari@bps.go.id', '42', '92200', '', '3100', '', 0),
('340011868', '', '196512081988021001', '', 'Urip Sabani', '', 'sabani@bps.go.id', '32', '92320', '92300', '3100', '', 0),
('340011948', '', '196608191988031001', '', 'Agus Sumarna', '', 'agus.sumarna@bps.go.id', '32', '92630', '92600', '3100', '', 0),
('340011998', '', '196904141988121001', '', 'Dwino Daries', ' B.Eng, M.T.I', 'dwino@bps.go.id', '42', '92600', '92600', '3100', 'Pranata Komputer Madya', 0),
('340012310', '', '196812281989031002', '', 'Ahmad Saefudin', '', 'asaefudin@bps.go.id', '33', '92210', '92200', '3100', '', 0),
('340012311', '', '196902011989032003', '', 'Nani Suciati', 'SE', 'nanisuci@bps.go.id', '34', '92630', '', '3100', '', 0),
('340012349', '', '196603271989032003', '', 'Idha Wuryaningsih', 'S.IP', 'idhaw@bps.go.id', '33', '92420', '92400', '3100', 'Statistisi Ahli Muda', 0),
('340012353', '', '196911221989031003', '', 'Kunto Sadewo', '', 'kunto.sadewo@bps.go.id', '32', '92140', '92100', '3100', '', 0),
('340012545', '', '196704301990032001', '', 'Klarawidya Puspita Rasman', 'SE,,M.SE', 'klarawidya@bps.go.id', '41', '92510', '92500', '3100', 'Statistisi Ahli Madya', 0),
('340012550', '', '196702221990031001', '', 'Ahmad Azhari ', 'S.Si', 'azhari@bps.go.id', '42', '92200', '', '3100', '', 0),
('340012589', '', '196706271990031004', '', 'Bedjo Sulaksono', 'S.IP', 'bedjo@bps.go.id', '34', '92140', '92100', '3100', '', 0),
('340012608', '', '196802081990032001', '', 'Sushinta Purwandari', 'SST', 'sushinta_indri@bps.go.id', '34', '92430', '92400', '3100', '', 0),
('340012645', '', '196812141991012001', '', 'Suryani Widarta', 'S.Si, ME', 'suryani@bps.go.id', '42', '92500', '92500', '3100', 'Statstisi Ahli Madya', 0),
('340012646', '', '196804151991012001', '', 'Els Arianti', 'S.Si.,MAP', 'elsarianti@bps.go.id', '41', '92420', '92400', '3100', '', 0),
('340012647', '', '196808031991011001', '', 'Akhmad Fikri ', 'SST', 'fikri@bps.go.id', '42', '92800', '', '3173', '', 0),
('340012728', '', '196601101991022001', '', 'Munawaroh ', 'SE, MAB', 'munaw@bps.go.id', '42', '92800', '', '3171', '', 0),
('340012871', '', '196702031991032001', 'Ir. ', 'Endah Nurjati', 'MAP', '', '41', '92430', '92400', '3100', 'Statistisi Ahli Muda', 0),
('340012955', '', '196910311991031002', '', 'Alfhonso Triantoro ', 'SE, M.Si.', 'alfo@bps.go.id', '42', '92800', '', '3101', '', 0),
('340013555', '', '196612281993012001', '', 'Ir. Nurika Parmiati', 'MAP', 'nurikaparmi@bps.go.id', '41', '92800', '92300', '3100', 'Statistisi Ahli Madya', 0),
('340013621', '', '196702051993032001', 'Ir. ', 'Dwi Paramita Dewi', 'ME', 'paramita@bps.go.id', '42', '92000', '92100', '3100', 'Statstisi Ahli Madya', 0),
('340014475', '', '196712261994022001', 'Dra', 'Eriyani Murwati Dianingsih', '', 'eriyani@bps.go.id', '34', '92130', '92100', '3100', '', 0),
('340014478', '', '196706101994022002', 'Ir. ', 'Siti Alifah', 'M.A.', 'alifah@bps.go.id', '41', '92230', '92200', '3100', 'Statistisi Ahli Muda', 0),
('340014496', '', '196602151994021001', 'Ir.', 'Banua Rambe', 'M.Si', 'brambe@bps.go.id', '42', '92800', '', '3172', '', 0),
('340014528', '', '197008151994031007', '', 'Biworo Prasongko', 'S.AP', 'biworo@bps.go.id', '33', '92130', '92100', '3100', '', 0),
('340014586', '', '197410191994031003', '', 'Aziz Kurniawan', 'S.Kom', 'azizk@bps.go.id', '33', '92150', '92100', '3100', '', 0),
('340014746', '', '196509301994031001', 'Drs', 'Patrianto', '', 'patrianto@bps.go.id', '34', '92140', '', '3100', '', 0),
('340014780', '', '196907301994032005', '', 'Sri Purwaningsih', '', 'ningsp@bps.go.id', '32', '92530', '92500', '3100', '', 0),
('340014813', '', '197007191994032001', '', 'Siti Nurjanah', '', 'snurjanah@bps.go.id', '32', '92130', '92100', '3100', '', 0),
('340015140', '', '197311161995122001', '', 'Qurratul Aini', 'S.Si.,M.Sc', 'aini@bps.go.id', '41', '92410', '92400', '3100', 'Statistisi Ahli Madya', 0),
('340015207', '', '197203081995121001', '', 'Hendra Setiawan', 'SST', 'hendras@bps.go.id', '34', '92220', '92200', '3100', 'Statistisi Ahli Muda', 0),
('340015481', '', '197607141997122001', '', 'Lilik Muslikhatin ', 'M.Si.', 'alice_lilik@bps.go.id', '41', '92500', '', '3100', 'Statistisi Ahli Muda', 0),
('340015483', '', '197508271997121001', '', 'Feri Prasetyo Nugroho', 'S.Si., M.E.', 'ferip@bps.go.id', '41', '92400', '92400', '3100', 'Statistisi Ahli Madya', 0),
('340015789', '', '197610251999012001', '', 'Tri Pramujiyanti', 'S.Si', 'tri.pramuji@bps.go.id', '32', '92210', '92200', '3100', '', 0),
('340015944', '', '197811211999122002', '', 'Dina Pratiwi', 'S.Si', 'dinapratiwi@bps.go.id', '34', '92610', '92600', '3100', 'Pranata Komputer Ahli Muda', 3),
('340016119', '', '197804112000122002', '', ' Favten Ari Pujiastuti', ' S.Si., SST, ME.', 'favtenari@bps.go.id', '41', '92800', '92300', '3175', 'Statistisi Ahli Madya', 0),
('340016177', '', '197812072000122001', '', 'Theresia Parwati', 'SST, M. I. Kom.', 'theres@bps.go.id', '41', '92600', '92200', '3100', 'Statistisi Ahli Madya', 0),
('340016278', '', '197804052000121007', '', 'Suryana', 'SST, M.Si.', 'suryana@bps.go.id', '42', '92100', '92200', '3100', '', 0),
('340016530', '', '198007182002121003', '', 'Muhammad Noval', 'SST, M.E.', 'masnov@bps.go.id', '41', '92530', '92500', '3100', 'Statistisi Ahli Madya', 0),
('340016892', '', '198002202002122001', '', 'Ruth Juliana Lumbantobing', '', 'ruth.juliana@bps.go.id', '32', '92310', '92300', '3100', '', 0),
('340016936', '', '198103082003122003', '', 'Linda Kusumawardani', 'SST., M.E.', 'lindakus@bps.go.id', '41', '92110', '92100', '3100', '', 0),
('340016969', '', '198012202003122001', '', 'Dwi Wahyuni', 'SST.,SE.,M.Si', 'dwi_wahyuni@bps.go.id', '41', '92310', '92300', '3100', 'Statistisi Ahli Madya', 0),
('340017176', '', '197112242003122002', '', 'Dwi Pudjiati', '', 'pudji.dwi@bps.go.id', '31', '92140', '92100', '3100', '', 0),
('340017263', '', '198205092003122001', '', 'Dian Anggraini', 'SE', 'dian.anggraini@bps.go.id', '34', '92120', '92100', '3100', '', 0),
('340017406', '', '198205202004122001', '', 'Budi Utami', 'SST, M.Si', 'budiutami@bps.go.id', '34', '92520', '92500', '3100', 'Statistisi Ahli Muda', 0),
('340017694', '', '197808022005022002', '', 'Dwi Agus Pujilestari', 'SE', 'dwiapl@bps.go.id', '32', '92410', '92400', '3100', '', 0),
('340018921', '', '197902242006041018', '', 'Saifullah', '', 'saifull@bps.go.id', '24', '92140', '92100', '3100', '', 0),
('340018923', '', '197707202006041019', '', 'Suyono', '', 'suyono@bps.go.id', '24', '92140', '92100', '3100', '', 0),
('340019149', '', '198412272007012004', '', 'Ardani Yustriana Dewi ', 'SST.,MT', 'ardani@bps.go.id', '34', '92630', '92600', '3100', 'Pranata Komputer Ahli Muda', 0),
('340051132', '', '198308022009022006', '', 'Fina Sri Agustina', 'S.Si, M.S.E, M.E', 'finas@bps.go.id', '33', '92430', '92400', '3100', '', 0),
('340051327', '', '197707132009022002', '', 'Solihatin', ' S.Si., MT', 'atin@bps.go.id', '33', '92620', '92600', '3100', 'Statistisi Ahli Muda', 0),
('340051342', '', '198002072009021003', '', 'Supendi', ' S.Si, M.A', 'supendi@bps.go.id', '33', '92310', '', '3100', '', 0),
('340051354', '', '198501152009022007', '', 'Trisnawati Wardah', 'S.E, M.S.E', 'trisna.wardah@bps.go.id', '32', '92110', '92100', '3100', '', 0),
('340053780', '', '198107012010031002', '', 'Yulius Antokida', 'S.Si, M.Si', 'antokida@bps.go.id', '33', '92300', '', '3100', '', 0),
('340054123', '', '198803112010122005', '', 'Nila Windiyarti', 'SST, M.Si', 'artinila@bps.go.id', '33', '92520', '92500', '3100', 'Statistisi Ahli Muda', 0),
('340054181', '', '198808172010122004', '', 'Mega Cahya Kristianti', 'SST', 'megacahya@bps.go.id', '32', '92230', '92200', '3100', '', 0),
('340054186', '', '198903152010122005', '', 'Steffi Riahta Sembiring', 'SST, MEKK', 'steffirs@bps.go.id', '33', '92310', '92300', '3100', 'Statistisi Ahli Muda', 0),
('340054258', '', '198901122010122005', '', 'Ratih Sari Dewi,', 'SST', 'rsdewi@bps.go.id', '32', '92520', '92500', '3100', '', 0),
('340054842', '', '198512262011011003', '', 'Ahmad Yunus, A.Md.', 'A.Md.', 'ahmad.yunus@bps.go.id', '24', '92140', '92100', '3100', '', 0),
('340054846', '', '198609052011011012', '', 'Imam Pambudi', 'A.Md.', 'pambudi@bps.go.id', '32', '92130', '92100', '3100', '', 0),
('340054851', '', '198609182011012022', '', 'Nur Ike Saptarini', 'S.Psi', 'nurike@bps.go.id', '33', '92120', '92100', '3100', '', 0),
('340055320', '', '197712022011012007', '', 'Elly Juliana Sukariati Br. Lumbantobing', ' S.Psi', 'ellyjs@bps.go.id', '33', '92120', '', '3100', '', 0),
('340055638', '', '198204192011011007', '', 'Kunradus', 'S.T.', 'kunradus@bps.go.id', '35', '92120', '92100', '3100', '', 0),
('340055877', '', '199003092012112001', '', 'Nurhani Restu Umi', 'SST', 'nurhani@bps.go.id', '32', '92630', '', '3100', '', 0),
('340056249', '', '199009292013112001', '', 'Dewi Saputri Ningsih', 'SST', 'dewisaputri@bps.go.id', '32', '92220', '92200', '3100', '', 0),
('340056302', '', '199011242013112001', '', 'Hastanti Sukoco Putri', 'SST, M.A', 'hastanti.sp@bps.go.id', '32', '92430', '92400', '3100', '', 0),
('340056436', '', '199010132013111001', '', 'Ronaldo Halomoan', ' SST', 'ronaldoh@bps.go.id', '33', '92630', '92600', '3100', '', 0),
('340056437', '', '199008052013111001', '', 'Ronnie Antonia ', 'SST', 'ronnieahutajulu@bps.go.id', '33', '92500', '', '3100', 'Statistisi Ahli Muda', 0),
('340057126', '', '199304032014121001', '', ' Marwan Wahyudin', ' SST', 'marwan.wahyudin@bps.go.id', '32', '92610', '92600', '3100', 'Pranata Komputer Ahli Pertama', 0),
('340057290', '', '199308132016022001', '', 'Annisa Nur Fadhilah', 'SST', 'annisa.fadhilah@bps.go.id', '32', '92520', '92500', '3100', '', 0),
('340057293', '', '199312012016021001', '', 'Anugrah Adi Dwi Yarto', 'SST', 'anugrah.adi@bps.go.id', '32', '92530', '92500', '3100', 'Statistisi Ahli Pertama', 0),
('340057376', '', '199208042016021001', '', 'Fakhriyanto', 'SST', 'fakhriyanto@bps.go.id', '32', '92610', '92600', '3100', 'Pranata Komputer Ahli Pertama', 0),
('340057716', '', '199404072017012001', '', 'Kadek Swarniati', 'SST', 'kadek.swarniati@bps.go.id', '32', '92220', '92200', '3100', '', 0),
('340057718', '', '199409092017012001', '', 'Mutiara Virgia Leran Putri', 'SST', 'mutiara.putri@bps.go.id', '32', '92510', '92500', '3100', '', 0),
('340057720', '', '199412012017012002', '', 'Yolanda Wilda Artati', 'SST', 'yolanda.artati@bps.go.id', '32', '92430', '92400', '3100', '', 0),
('340057764', '', '199502042017012001', '', 'Febrianti Dwi Wigati', 'SST', 'febrianti.wigati@bps.go.id', '32', '92120', '92100', '3100', '', 0),
('340057907', '', '199501052017011001', '', 'Dimas Hafizh', 'SST', 'dimas.hafizh@bps.go.id', '32', '92210', '92200', '3100', 'Statistisi Ahli Pertama', 0),
('340058129', '', '199606252018022001', '', 'Amirah Fatinah', 'SST', 'amirah.fatinah@bps.go.id', '31', '92140', '92100', '3100', '', 0),
('340058295', '', '199503162018021001', '', 'Indra Gunawan', 'SST', 'indra.gunawan@bps.go.id', '32', '92870', '', '3174', '', 0),
('340058499', '', '199511072018022001', '', 'Sri Karina Putri Br. Karo-Karo', 'SST', 'sri.karina@bps.go.id', '32', '92140', '', '3100', '', 0),
('340058748', '', '199603022019012001', '', 'Galuh Permata Sari ', 'S.Tr.Stat.', 'galuhps@bps.go.id', '31', '92630', '', '3100', '', 0),
('340058881', '', '199704092019011002', '', 'Naufal Rasyid', 'S.Tr.Stat.', 'naufal.rasyid@bps.go.id', '32', '92300', '', '3100', '', 0),
('340059752', '', '199706072019122001', '', 'Rosalinda Regita ', 'S.Tr.Stat.', 'rosa.regita@bps.go.id', '31', '92300', '', '3100', '', 0),
('340059799', '', '199701272019122001', '', 'Vilda Tri Lestari Simbolon ', 'S.Tr.Stat.', 'vilda.tri@bps.go.id', '31', '92610', '92600', '3100', '', 0),
('340059928', '', '200004032019122001', '', 'Erlina Listianingsih', 'A.P.Kb.N.', 'erlina.listianingsih@bps.go.id', '21', '92130', '92100', '3100', '', 0),
('340060133', '', '199612202021041001', '', 'Hazanul Zikra ', 'S.Tr.Stat', 'hazanul.zikra@bps.go.id', '31', '92400', '', '3100', '', 0),
('340061195', '', '199107132022031005', '', 'Arisonaldi Sibagariang', 'SE', 'arisonaldis@bps.go.id', '31', '92150', '', '3100', '', 0),
('340062266', '', '200105142023022002', '', 'Erikha Anindita Putri Hidayat', 'A.Md.Ak.', 'erikha.anindita@bps.go.id', '23', '92130', '', '3100', '', 0),
('340062288', '', '199311122023212035', '', 'Novi Fatheka Trisnawati', 'S.Ikom', 'novifatheka-pppk@bps.go.id', '31', '92100', '', '3100', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_pegawai`
--
ALTER TABLE `master_pegawai`
  ADD PRIMARY KEY (`niplama`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
