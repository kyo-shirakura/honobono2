<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318150054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO dtb_main_bank (id, invoice_bank_name, invoice_bank_code, invoice_bank_branch_name, invoice_bank_branch_code, invoice_bank_account_type, invoice_bank_account_number, invoice_bank_account_name, invoice_bank_account_name_kana, payslip_bank_name, payslip_bank_code, payslip_bank_branch_name, payslip_bank_branch_code, payslip_bank_account_type, payslip_bank_account_number, payslip_bank_account_name, payslip_bank_account_name_kana, visible, create_date, update_date, discriminator_type) VALUES ('1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2026-01-01 10:00:00.000000', '2026-01-01 10:00:00.000000', 'mainbank')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
