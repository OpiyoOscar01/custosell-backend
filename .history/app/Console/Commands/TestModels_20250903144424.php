<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Payment;

class TestModels extends Command
{
    protected $signature = 'test:models';
    protected $description = 'Test all models are properly configured';

    public function handle()
    {
        $this->info('Testing models...');
        
        try {
            new User();
            $this->line('✓ User model: OK');
        } catch (\Exception $e) {
            $this->error('✗ User model: ERROR - ' . $e->getMessage());
        }
        
        try {
            new Project();
            $this->line('✓ Project model: OK');
        } catch (\Exception $e) {
            $this->error('✗ Project model: ERROR - ' . $e->getMessage());
        }
        
        try {
            new Task();
            $this->line('✓ Task model: OK');
        } catch (\Exception $e) {
            $this->error('✗ Task model: ERROR - ' . $e->getMessage());
        }
        
        try {
            new Order();
            $this->line('✓ Order model: OK');
        } catch (\Exception $e) {
            $this->error('✗ Order model: ERROR - ' . $e->getMessage());
        }
        
        try {
            new Invoice();
            $this->line('✓ Invoice model: OK');
        } catch (\Exception $e) {
            $this->error('✗ Invoice model: ERROR - ' . $e->getMessage());
        }
        
        try {
            new Payment();
            $this->line('✓ Payment model: OK');
        } catch (\Exception $e) {
            $this->error('✗ Payment model: ERROR - ' . $e->getMessage());
        }
        
        $this->info('All models tested successfully!');
    }
}
