<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    //home
	public function index()
	{
        redirect('welcome/menu');
	}

    //login
	public function login() 
    {
        $username = $this->input->post('username',true);
        $password = $this->input->post('password',true);
        $query = $this->login_model->processLogin($username,$password);
        $name = '-1';
        for($i=1;$i<=$query->num_rows();++$i){
              $name = $query->row($i)->Name;
        }
        if($name!='-1'){
            setcookie('name', $name, time() + (86400 * 30));
            redirect('welcome/home');
        }
        else{
			$this->session->set_flashdata('err','Username or Password is wrong!'); 
            redirect('welcome/log');
        }
    }
    public function logout()
    {
        setcookie('name', '', time()  -1);
        redirect('welcome/index');
    }



    //kbm

    //untuk user meminjam
    public function kbm_booking_car()
    {
        $waktuPinjamCombine = $this->input->post('waktuPinjam_jam',true).":".$this->input->post('waktuPinjam_menit',true);
        $nomorPolisi = $this->input->post('nomorPolisi',true);
        $peminjam = $this->input->post('peminjam',true);
        $nik = $this->input->post('nik',true);
        $nomorTelepon = $this->input->post('nomorTelepon',true);
        $keperluan = $this->input->post('keperluan',true);
        $kmAmbil = '';
        $kmKembali = '';
        $durasi = $this->input->post('durasi',true);
        $tanggalPinjam = $this->input->post('tanggalPinjam',true);
        $waktuPinjam = $waktuPinjamCombine;
        $waktuAmbil = '';
        $tanggalKembali = '';
        $waktuKembali = '';
        $pemberi = '';
        $status = 0;
        /*get result*/
        $result = $this->kbm_model->insertRent($nomorPolisi, $peminjam, $nik, $nomorTelepon, $keperluan, $kmAmbil, $kmKembali, $durasi, $tanggalPinjam, $waktuPinjam, $waktuAmbil, $tanggalKembali, $waktuKembali, $pemberi, $status);
        if($result==1)
        {
            /*insert berhasil*/
            redirect('welcome/done');
        }
        else
        {
            /*insert gagal*/
            redirect('welcome/form_pinjam_kbm');
        }
    }

    //untuk admin approve booking
    public function kbm_approve_booking()
    {
        $nomorPolisi = $this->input->post('nomorPolisi',true);
        $peminjam = $this->input->post('peminjam',true);
        $nik = $this->input->post('nik',true);
        $nomorTelepon = $this->input->post('nomorTelepon',true);
        $keperluan = $this->input->post('keperluan',true);
        $kmAmbil = $this->input->post('kmAmbil',true);
        $durasi = $this->input->post('durasi',true);
        $tanggalPinjam = $this->input->post('tanggalPinjam',true);
        $waktuPinjam = $this->input->post('waktuPinjam',true);
        $pemberi = $this->input->post('pemberi',true);
        /*get result*/
        $result = $this->kbm_model->approveBooking($nomorPolisi, $peminjam, $nik, $nomorTelepon, $keperluan, $kmAmbil, $durasi, $tanggalPinjam, $waktuPinjam, $pemberi);
        if($result>0)
        {
            /*approve berhasil*/
            redirect('welcome/Peminjaman');
        }
        else
        {
            /*approve gagal*/
            redirect('welcome/index');
        }
    }

    //untuk admin membatalkan booking 
    public function kbm_decline_booking()
    {
        $nomorPolisi = $this->input->post('nomorPolisi',true);
        $peminjam = $this->input->post('peminjam',true);
        $nik = $this->input->post('nik',true);
        $nomorTelepon = $this->input->post('nomorTelepon',true);
        $keperluan = $this->input->post('keperluan',true);
        $durasi = $this->input->post('durasi',true);
        $tanggalPinjam = $this->input->post('tanggalPinjam',true);
        $waktuPinjam = $this->input->post('waktuPinjam',true);
        $pemberi = $this->input->post('pemberi',true);
        /*get result*/
        $result = $this->kbm_model->declineBooking($nomorPolisi, $peminjam, $nik, $nomorTelepon, $keperluan, $durasi, $tanggalPinjam, $waktuPinjam, $pemberi);

        if($result>0)
        {
            /*approve berhasil*/
            redirect('welcome/Peminjaman');
        }
        else
        {
            /*approve gagal*/
            redirect('welcome/index');
        }
    }

    //untuk menampilkan daftar pinjam mobil sekarang
    public function kbm_show_rent_car()
    {
        $result = $this->kbm_model->showRent();
        if(!empty($result))
        {
            $data['showRent'] = $result;
            $this->load->view('testDB',$data);
        }
    }

    //untuk update status peminjaman setelah user selesai mengembalikan mobil
    public function kbm_return_car()
    {
        $nomorPolisi = $this->input->post('nomorPolisi',true);
        $peminjam = $this->input->post('peminjam',true);
        $nik = $this->input->post('nik',true);
        $nomorTelepon = $this->input->post('nomorTelepon',true);
        $keperluan = $this->input->post('keperluan',true);
        $kmKembali = $this->input->post('kmKembali',true);
        $durasi = $this->input->post('durasi',true);
        $tanggalPinjam = $this->input->post('tanggalPinjam',true);
        $waktuPinjam = $this->input->post('waktuPinjam',true);
        $waktuAmbil = $this->input->post('waktuAmbil',true);
        $pemberi = $this->input->post('pemberi',true);
        /*get result*/
        $result = $this->kbm_model->carReturning($nomorPolisi, $peminjam, $nik, $nomorTelepon, $keperluan, $kmKembali, $durasi, $tanggalPinjam, $waktuPinjam, $waktuAmbil, $pemberi);
        if($result==1)
        {
            /*insert berhasil*/
            redirect('welcome/status_peminjaman_admin');
        }
        else
        {
            /*insert gagal*/
            redirect('welcome/home');
        }
    }

    //menampilkan jumlah permintaan booking
    public function kbm_booking_count(){
        $data['bookingCount'] = $this->kbm_model->bookingCount();
        $this->load->view('notification',$data);
    }

    //Redirect link
    public function home(){$this->load->view('home');}
    public function done(){$this->load->view('done');}
    public function menu(){$this->load->view('menu');}
    public function log(){$this->load->view('login');}
    
    public function status_peminjaman()
    {
        $result = $this->kbm_model->showRent();
        // if(!empty($result))
        // {
        $data['showRent'] = $result;
        $this->load->view('status_peminjaman',$data);
        // }
    }

    public function status_peminjaman_admin()
    {
        $result = $this->kbm_model->showRent();
        $data['showRent'] = $result;
        $this->load->view('status_peminjaman_admin',$data);
    }

    public function history()
    {
        $result = $this->kbm_model->showHistoryApprove();
        $data['showHistoryApprove'] = $result;
        $result2 = $this->kbm_model->showHistoryDisapprove();
        $data2['showHistoryDisapprove'] = $result2;
        $this->load->view('history',$data);
    }

    public function history_terima()
    {
        $result = $this->kbm_model->showHistoryApprove();
        $data['showHistoryApprove'] = $result;
        $this->load->view('history_terima',$data);
    }

    public function history_tolak()
    {
        $result = $this->kbm_model->showHistoryDisapprove();
        $data['showHistoryDisapprove'] = $result;
        $this->load->view('history_tolak',$data);
    }

    //export to excel
    public function export_history_terima()
    {
        $result = $this->kbm_model->showHistoryApprove();
        // $data['showHistoryApprove'] = $result;
        $data = array( 'title' => 'History KBM diizinkan',
                'buku' => $result);
        $this->load->view('report_approve',$data);
    }

    public function export_history_tolak()
    {
        $result = $this->kbm_model->showHistoryDisapprove();
        // $data['showHistoryDisapprove'] = $result;
        $data = array( 'title' => 'History KBM tidak diizinkan',
                'buku' => $result);
        $this->load->view('report_disapprove',$data);
    }

    public function peminjaman()
    {
        $result = $this->kbm_model->showBooking();
        $data['showBooking'] = $result;
        $this->load->view('Peminjaman',$data);/*di load ke admin >> yg akan di approve*/
    }
    public function form_pinjam_kbm()
    {
        $data['showCar'] = $this->kbm_model->showCar();
        $this->load->view('form_pinjam_kbm',$data);
    }


   //Export PDF

    // public function toPdf(){
    //     $this->load->library('Pdf');

    //     $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
    //     $pdf->SetTitle('My Title');
    //     $pdf->SetHeaderMargin(30);
    //     $pdf->SetTopMargin(20);
    //     $pdf->setFooterMargin(20);
    //     $pdf->SetAutoPageBreak(true);
    //     $pdf->SetAuthor('Author');
    //     $pdf->SetDisplayMode('real', 'default');

    //     $pdf->AddPage();

    //     $pdf->Write(5, 'Some sample text');

    //     $pdf->Output('My-File-Name.pdf', 'I');
    // }

}