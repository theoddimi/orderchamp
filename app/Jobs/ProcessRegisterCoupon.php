<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Discount;
use Illuminate\Support\Str;

class ProcessRegisterCoupon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The discount instance.
     *
     * @var \App\Models\Discount
     */
    protected $discount;
    protected $user;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Discount $discount, \Illuminate\Foundation\Auth\User $user)
    {
        $this->discount = $discount;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $this->user->discounts()->attach($this->discount, ['active' => 1, 'token' => Str::random(30)]);
    }
}
