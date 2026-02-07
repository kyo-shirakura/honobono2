<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Eccube\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice
 *
 * @ORM\Table(name="dtb_invoice")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\InvoiceRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Invoice extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Eccube\Entity\Customer
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * })
     */
    private $Customer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issue_ym", type="datetimetz")
     */
    private $issue_ym;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pdf_file", type="string", length=255, nullable=true)
     */
    private $pdf_file;

    /**
     * @var int|null
     *
     * @ORM\Column(name="sendmail", type="integer", nullable=true)
     */
    private $sendmail;

    /**
     * @var int|null
     *
     * @ORM\Column(name="revision", type="integer", nullable=false, options={"default":1})
     */
    private $revision;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetimetz")
     */
    private $create_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetimetz")
     */
    private $update_date;


    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function __toString()
    {
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->Customer;
    }

    /**
     * @param Customer $Customer
     *
     * @return Invoice
     */
    public function setCustomer(Customer $Customer = null)
    {
        $this->Customer = $Customer;

        return $this;
    }

    /**
     * Set issue_ym.
     *
     * @param string $issue_ym
     *
     * @return Business
     */
    public function setIssueYm($issue_ym)
    {
        $this->issue_ym = $issue_ym;

        return $this;
    }

    /**
     * Get issue_ym.
     *
     * @return string
     */
    public function getIssueYm()
    {
        return $this->issue_ym;
    }

    /**
     * Set pdf_file.
     *
     * @param string $pdf_file
     *
     * @return Business
     */
    public function setPdfFile($pdf_file)
    {
        $this->pdf_file = $pdf_file;

        return $this;
    }

    /**
     * Get pdf_file.
     *
     * @return string
     */
    public function getPdfFile()
    {
        return $this->pdf_file;
    }

    /**
     * Set sendmail.
     *
     * @param integer $sendmail
     *
     * @return Payslip
     */
    public function setSendmail($sendmail)
    {
        $this->sendmail = $sendmail;

        return $this;
    }

    /**
     * Get sendmail.
     *
     * @return integer
     */
    public function getSendmail()
    {
        return $this->sendmail;
    }

    /**
     * Set revision.
     *
     * @param integer $revision
     *
     * @return Payslip
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;

        return $this;
    }

    /**
     * Get revision.
     *
     * @return integer
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return Invoice
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return Invoice
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get updateDate.
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Constructs the object
     *
     * @see http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     *
     * @return void
     *
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->salt) = unserialize($serialized);
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
