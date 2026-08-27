<?php
/**
 * UM Dynamic Faculty Footer
 * ---------------------------------------------------------------
 * Footer yang warnanya (gradient card + aksen fakultas) otomatis
 * menyesuaikan subdomain yang sedang diakses, mis:
 *   - fip.um.ac.id     -> gradient putih ke biru, aksen putih
 *   - sastra.um.ac.id  -> gradient kuning ke kuning tua, aksen F1C300
 *   - um.ac.id (root)  -> skema warna default
 *
 * Cara pakai: taruh shortcode [um_footer] di halaman/template.
 * Cocok dipakai lewat WP-Pusher karena berbentuk plugin .php biasa.
 * ---------------------------------------------------------------
 */

if (!defined('ABSPATH')) {
    exit; // no direct access
}

/*
|--------------------------------------------------------------------
| 1. KONFIGURASI WARNA PER FAKULTAS
|--------------------------------------------------------------------
| Key   = prefix subdomain (sebelum ".um.ac.id")
| Value = array berisi:
|   'base'   => warna dasar fakultas (wajib)
|   'grad_start' => (opsional) override warna awal gradient
|   'grad_end'   => (opsional) override warna akhir gradient
|   'accent'      => (opsional) override "warna fakultas" / aksen
|
| Kalau grad_start / grad_end / accent TIDAK diisi, sistem akan
| menghitung otomatis dari 'base' (lihat um_footer_derive_colors()).
|
| CATATAN: prefix di bawah ini yang sudah saya pastikan sesuai
| instruksi kamu cuma 'fip' dan 'sastra'. Sisanya masih tebakan
| nama subdomain dan warna dasar dari palet gambar kamu — cek dan
| sesuaikan prefix-nya dengan subdomain resmi tiap fakultas ya.
*/
function um_footer_faculty_map()
{
    return [
        'brand' => [ // Fakultas Ilmu Pendidikan
            'base'       => '#FFFFFF',
            'grad_start' => '#FFFFFF',
            'grad_end'   => '#8CB2FF',
            'accent'     => '#FFFFFF',
            'logo'       => 'https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FIP-2.webp',
        ],
        'fip' => [ // Fakultas Ilmu Pendidikan
            'base'       => 'rgba(10, 30, 80, 0.75)',
            'grad_start' => 'rgba(10, 30, 80, 0.75)',
            'grad_end'   => '#0b1529',
            'accent'     => 'rgba(10, 30, 80, 0.75)',
            'logo'       => 'https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FIP-2.webp',
        ],
        'sastra' => [ // Fakultas Sastra
            'base'       => '#FFCE00',
            'grad_start' => '#FFCE00',
            'grad_end'   => '#BC9800',
            'accent'     => '#F1C300',
            'logo'       => 'https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FS.webp',
        ],

        // --- Sisanya: baru warna dasar dari palet, gradient & aksen
        //     dihitung otomatis. Ganti prefix di kiri sesuai domain
        //     resmi, dan lengkapi grad_start/grad_end/accent/logo kalau
        //     sudah ada nilai pastinya dari desain.
        'fik'     => ['base' => '#66E6E6','grad_start' => '#66E6E6',
            'grad_end'   => '#46A0A0',
            'accent'     => '#66E6E6',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FIK-.webp"], // Fakultas Ilmu Keolahragaan
        'ft'      => ['base' => '#FF3131','grad_start' => '#FF3131',
            'grad_end'   => '#B62020',
            'accent'     => '#FF3131',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FT.webp"], // Fakultas Teknik
        'pasca-ft'      => ['base' => '#FF3131','grad_start' => '#FF3131',
            'grad_end'   => '#B62020',
            'accent'     => '#FF3131',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FT.webp"], // Fakultas Pasca Teknik
        'fmipa'   => ['base' => '#00700B','grad_start' => '#00700B',
            'grad_end'   => '#015509',
            'accent'     => '#00700B',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FMIPA.webp"], // Fakultas MIPA
        'pasca'   => ['base' => '#942D1D','grad_start' => '#942D1D',
            'grad_end'   => '#762417',
            'accent'     => '#942D1D',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/SEKOLAH-PASCASARJANA.webp"], // Pascasarjana / Graduate School
        'fis'     => ['base' => '#5C0098','grad_start' => '#5C0098',
            'grad_end'   => '#43006E',
            'accent'     => '#5C0098',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FIS.webp"], // Fakultas Ilmu Sosial
        'feb'     => ['base' => '#477EBB','grad_start' => '#477EBB',
            'grad_end'   => '#1262BB',
            'accent'     => '#477EBB',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FEB.webp"], // Fakultas Ekonomi dan Bisnis
        'fppsi'   => ['base' => '#EA028C','grad_start' => '#EA028C',
            'grad_end'   => '#A60063',
            'accent'     => '#EA028C',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FPSI.webp"], // Fakultas Psikologi
        'vokasi'  => ['base' => '#FF7F29','grad_start' => '#FF7F29',
            'grad_end'   => '#B75B1D',
            'accent'     => '#FF7F29',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/VOKASI.webp"], // Fakultas Vokasi
        'fk'      => ['base' => '#3B7C36','grad_start' => '#3B7C36',
            'grad_end'   => '#2B5927',
            'accent'     => '#3B7C36',
            'logo'       => "https://pasca-ft.um.ac.id/wp-content/uploads/2026/08/FK.webp"], // Fakultas Kedokteran
    ];
}

// Logo default (dipakai kalau subdomain tidak dikenali, atau fakultas
// belum diisi 'logo' di um_footer_faculty_map() -> masih null)
function um_footer_default_logo()
{
    return 'https://ppdh.um.ac.id/wp-content/uploads/2026/06/Direktorat-PPDH.png';
}

// Warna default kalau subdomain tidak dikenali / diakses dari root um.ac.id
function um_footer_default_colors()
{
    return um_footer_derive_colors('#0762D9');
}

/*
|--------------------------------------------------------------------
| 2. HELPER WARNA (lighten / darken hex)
|--------------------------------------------------------------------
*/
function um_footer_hex_to_rgb($hex)
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
}

function um_footer_rgb_to_hex($rgb)
{
    return sprintf('#%02X%02X%02X', ...array_map(fn($c) => max(0, min(255, (int) round($c))), $rgb));
}

// $percent positif = lebih terang (campur putih), negatif = lebih gelap (campur hitam)
function um_footer_shade($hex, $percent)
{
    [$r, $g, $b] = um_footer_hex_to_rgb($hex);
    $mix = $percent > 0 ? 255 : 0;
    $p = abs($percent) / 100;

    return um_footer_rgb_to_hex([
        $r + ($mix - $r) * $p,
        $g + ($mix - $g) * $p,
        $b + ($mix - $b) * $p,
    ]);
}

// Dari 1 warna dasar, hasilkan set lengkap: grad_start, grad_end, accent
function um_footer_derive_colors($base, $overrides = [])
{
    return [
        'grad_start' => $overrides['grad_start'] ?? um_footer_shade($base, 35),
        'grad_end'   => $overrides['grad_end'] ?? um_footer_shade($base, -35),
        'accent'     => $overrides['accent'] ?? $base,
    ];
}

/*
|--------------------------------------------------------------------
| 3. DETEKSI FAKULTAS DARI SUBDOMAIN
|--------------------------------------------------------------------
*/
function um_footer_detect_colors()
{
    $host = strtolower($_SERVER['HTTP_HOST'] ?? '');
    // ambil prefix sebelum ".um.ac.id" (mis. "fip.um.ac.id" -> "fip")
    $prefix = preg_replace('/\.um\.ac\.id$/', '', $host);
    $prefix = ($prefix === $host) ? '' : $prefix; // bukan domain um.ac.id sama sekali

    $map = um_footer_faculty_map();

    if ($prefix !== '' && isset($map[$prefix])) {
        $entry = $map[$prefix];
        return um_footer_derive_colors($entry['base'], $entry);
    }

    return um_footer_default_colors();
}

// Cari logo fakultas dari subdomain yang sedang diakses.
// Kalau fakultas dikenali tapi 'logo' belum diisi (masih null),
// atau subdomain tidak dikenali sama sekali -> fallback ke logo default.
function um_footer_detect_logo()
{
    $host = strtolower($_SERVER['HTTP_HOST'] ?? '');
    $prefix = preg_replace('/\.um\.ac\.id$/', '', $host);
    $prefix = ($prefix === $host) ? '' : $prefix;

    $map = um_footer_faculty_map();

    if ($prefix !== '' && isset($map[$prefix]) && !empty($map[$prefix]['logo'])) {
        return $map[$prefix]['logo'];
    }

    return um_footer_default_logo();
}

/*
|--------------------------------------------------------------------
| 4. RENDER FOOTER
|--------------------------------------------------------------------
*/
function um_footer_render()
{
    $c = um_footer_detect_colors();
    $logo = um_footer_detect_logo();

    ob_start();
    ?>
    <style>
        .um-footer-wrap .footer-um {
            background: #012060;
            position: relative;
            overflow: hidden;
            padding: 28px 0;
        }

        .um-footer-wrap .footer-container {
            margin: auto;
            position: relative;
            z-index: 2;
        }

        /* CARD INFO */
        .um-footer-wrap .top-card {
            background: linear-gradient(90deg, <?php echo esc_attr($c['grad_start']); ?> 60%, <?php echo esc_attr($c['grad_end']); ?>);
            border-radius: 15px;
            padding: 25px 40px;
            margin-bottom: 30px;
        }

        .um-footer-wrap .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .um-footer-wrap .logo-area img {
            height: 65px;
        }

        .um-footer-wrap .contact-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .um-footer-wrap .contact-item {
            color: #fff;
            font-size: 16px;
        }

        /* MENU */
        .um-footer-wrap .menu-card {
            border-radius: 15px;
            padding: 35px;
        }

        .um-footer-wrap .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .um-footer-wrap .menu-col hr {
            border: 0;
            height: 2px;
            background-color: <?php echo esc_attr($c['accent']); ?>;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .um-footer-wrap .menu-col a {
            display: block;
            color: #fff !important;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 14px;
            line-height: 1.5;
        }

        /* .um-footer-wrap .menu-col a:hover {
            color: !important;
        } */

        /* FOOTER BOTTOM */
        .um-footer-wrap .footer-bottom {
            display: flex;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            border-radius: 13px;
            margin-top: 25px;
            background-color: #00153F;
            padding: 12px 20px;
        }

        .um-footer-wrap .footer-bottom a {
            color: #fff !important;
            text-decoration: none;
            margin: 0 15px;

        }

        /* BACKGROUND FOTO + OVERLAY GRADIENT DINAMIS */
        .um-footer-wrap .footer-bg {
            position: relative;
            padding: 60px 50px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            background:
                linear-gradient(
                    <?php echo esc_attr($c['grad_start']); ?>BF,
                    <?php echo esc_attr($c['grad_end']); ?>BF
                ),
                url("https://um.ac.id/wp-content/uploads/2026/03/9c1b5816c00d6e3cd04854792237040393f74333-scaled-e1776176101516.png")
                center 100% / cover no-repeat !important;
        }

        .um-footer-wrap .footer-bg::before {
            content: "";
            inset: 0;
            position: absolute;
            background: rgba(255, 255, 255, 0.02);
        }

        .um-footer-wrap .footer-bg .elementor-container {
            position: relative;
            z-index: 2;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .um-footer-wrap .menu-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .um-footer-wrap .contact-row {
                flex-direction: column;
            }
            .um-footer-wrap .footer-bottom{
                flex-direction: column;
                gap: 12px;
            }
        }

        @media (max-width: 576px) {
            .um-footer-wrap .menu-grid {
                grid-template-columns: 1fr;
            }
            .um-footer-wrap .top-card {
                padding: 25px;
            }
            .um-footer-wrap .menu-card {
                padding: 25px;
            }
        }
    </style>

    <div class="um-footer-wrap">
        <footer class="footer-um">
            <div class="footer-container">
                <!-- TOP INFO -->
                <div class="top-card">
                    <div class="logo-area">
                        <img src="<?php echo esc_url($logo); ?>" alt="Logo Fakultas" />
                    </div>

                    <div class="contact-row">
                        <div class="contact-item">✉ ppid@um.ac.id | humas@um.ac.id</div>
                        <div class="contact-item">☎ (0341)551312</div>
                        <div class="contact-item">👨‍🏫 Prof. Dr. Hariyono, M.Pd.</div>
                        <div class="contact-item">📅 13 September 1965</div>
                    </div>
                </div>

                <!-- MENU -->
                <div class="menu-card footer-bg">
                    <div class="menu-grid">
                        <div class="menu-col">
                            <hr />
                            <a href="https://sekretariat.um.ac.id/">Sekretariat Universitas</a>
                            <a href="https://wbs.um.ac.id/">Satgas PPKS</a>
                            <a href="https://spi.um.ac.id/">Badan Pengawas Internal</a>
                            <a href="https://bpm.um.ac.id/">Badan Pengembangan Usaha dan Dana Abadi</a>
                            <a href="https://lp2m.um.ac.id/">Lembaga Penelitian dan Pengabdian Kepada Masyarakat</a>
                            <a href="https://lppp.um.ac.id/">Lembaga Pengembangan Pendidikan dan Pengajaran</a>
                        </div>

                        <div class="menu-col">
                            <hr />
                            <a href="https://akademik.um.ac.id/">Direktorat Pendidikan</a>
                            <a href="https://kemahasiswaan.um.ac.id/">Direktorat Kemahasiswaan & Alumni</a>
                            <a href="https://psdmk.um.ac.id/">Direktorat Sumber Daya Manusia & Keuangan</a>
                            <a href="https://dspa.um.ac.id/">Direktorat Sarana, Prasarana, & Aset</a>
                            <a href="https://inovasi.um.ac.id/">Direktorat Inovasi</a>
                            <a href="https://ppdh.um.ac.id/">Direktorat Perencanaan, Pemeringkatan, Data & Humas</a>
                        </div>

                        <div class="menu-col">
                            <hr />
                            <a href="https://lib.um.ac.id/">UPT Perpustakaan</a>
                            <a href="https://lab-pancasila.um.ac.id/">UPT Laboratorium Pancasila</a>
                            <a href="https://ulpbj.um.ac.id/">UPT Layanan Pengadaan</a>
                            <a href="https://oia.um.ac.id/">UPT Kantor Urusan Internasional</a>
                            <a href="https://lsp.um.ac.id/">UPT Lembaga Sertifikasi Profesi</a>
                            <a href="https://psbbi.um.ac.id/">UPT Pusat Studi Bahasa dan Budaya Indonesia</a>
                            <a href="https://integratedlab.um.ac.id/">UPT Laboratorium Terpadu</a>
                        </div>

                        <div class="menu-col">
                            <hr />
                            <a href="https://psl.um.ac.id/">UPT Pengelola Sekolah Laboratorium UM</a>
                            <a href="https://pakb.um.ac.id/">UPT Pelaksana Akademik di Kampus Blitar</a>
                            <a href="https://lpa.um.ac.id/">UPT Laboratorium Pendidikan Agama</a>
                            <a href="https://publika.um.ac.id/">UPT Publikasi Ilmiah</a>
                            <a href="https://layanan-kesehatan.um.ac.id/">UPT Layanan Kesehatan</a>
                            <a href="https://ptik.um.ac.id/">UPT Pusat Teknologi Informasi dan Komunikasi</a>
                            <a href="https://bb.um.ac.id/">UPT Balai Bahasa</a>
                        </div>
                    </div>
                </div>

                <!-- FOOTER BOTTOM -->
                <div class="footer-bottom">
                    © <?php echo esc_html(date('Y')); ?> Universitas Negeri Malang
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat Penggunaan</a>
                    <a href="#">Peta Situs</a>
                </div>
            </div>
        </footer>
    </div>
    <?php
    return ob_get_clean();
}

/*
|--------------------------------------------------------------------
| 5. SHORTCODE
|--------------------------------------------------------------------
| Pemakaian: taruh [um_footer] di halaman / widget / template PHP
| (via do_shortcode() kalau dipanggil langsung dari file tema).
*/
add_shortcode('um_footer', 'um_footer_render');