<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    /**
     * @var array<string, array{label: string, description: string, tone: string}>
     */
    public const STATUSES = [
        self::STATUS_PENDING => [
            'label' => 'Menunggu konfirmasi',
            'description' => 'Pesanan masuk. Admin akan mengecek data dan menghubungi kamu.',
            'tone' => 'warning',
        ],
        self::STATUS_CONFIRMED => [
            'label' => 'Dikonfirmasi',
            'description' => 'Paket sudah dicek dan pesanan siap masuk antrean pengerjaan.',
            'tone' => 'info',
        ],
        self::STATUS_IN_PROGRESS => [
            'label' => 'Sedang dikerjakan',
            'description' => 'Tim sedang mengerjakan pesananmu. Progres diperbarui oleh admin.',
            'tone' => 'info',
        ],
        self::STATUS_COMPLETED => [
            'label' => 'Selesai',
            'description' => 'Pesanan selesai. Cek kembali akun dan hubungi admin bila ada kendala.',
            'tone' => 'success',
        ],
        self::STATUS_CANCELLED => [
            'label' => 'Dibatalkan',
            'description' => 'Pesanan dibatalkan. Hubungi admin jika kamu membutuhkan penjelasan.',
            'tone' => 'danger',
        ],
    ];

    /**
     * @var array<string, string>
     */
    public const PAYMENT_STATUSES = [
        'unpaid' => 'Belum dibayar',
        'waiting_confirmation' => 'Menunggu verifikasi',
        'paid' => 'Lunas',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'order_code',
        'user_id',
        'service_package_id',
        'package_name',
        'package_unit',
        'unit_price',
        'stars',
        'total_price',
        'game_nickname',
        'game_id',
        'server_id',
        'login_method',
        'whatsapp',
        'payment_method',
        'notes',
        'status',
        'payment_status',
        'admin_note',
        'completed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_price' => 'integer',
            'stars' => 'integer',
            'total_price' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class, 'service_package_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(OrderEvent::class)->latest();
    }

    public static function generateOrderCode(): string
    {
        do {
            $code = 'DS-' . now()->format('ymd') . '-' . Str::upper(Str::random(5));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? Str::headline($this->status);
    }

    public function getStatusDescriptionAttribute(): string
    {
        return self::STATUSES[$this->status]['description'] ?? '';
    }

    public function getStatusToneAttribute(): string
    {
        return self::STATUSES[$this->status]['tone'] ?? 'neutral';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? Str::headline($this->payment_status);
    }

    public function getTotalPriceLabelAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getUnitPriceLabelAttribute(): string
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_IN_PROGRESS], true);
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}
