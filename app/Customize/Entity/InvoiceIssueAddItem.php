<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Customize\Entity\Staff;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice
 *
 * @ORM\Table(name="dtb_invoice_issue_add_item")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\InvoiceIssueAddItemRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class InvoiceIssueAddItem extends AbstractEntity
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
     * @var \Customize\Entity\Staff
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Staff")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="staff_id", referencedColumnName="id")
     * })
     */
    private $Staff;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issue_ym", type="datetimetz")
     */
    private $issue_ym;

    /**
     * @var string|null
     *
     * @ORM\Column(name="item_title", type="string", length=256, nullable=true)
     */
    private $item_title;

    /**
     * @var int|null
     *
     * @ORM\Column(name="item_amout", type="integer", nullable=true)
     */
    private $item_amout;

    /**
     * @var string
     *
     * @ORM\Column(name="item_remark", type="string", nullable=true)
     */
    private $item_remark;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", nullable=true)
     */
    private $remark;

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
     * @return Staff|null
     */
    public function getStaff(): ?Staff
    {
        return $this->Staff;
    }

    /**
     * @param Staff $Staff
     *
     * @return InvoiceIssueAddItem
     */
    public function setStaff(Staff $Staff = null)
    {
        $this->Staff = $Staff;

        return $this;
    }

    /**
     * Set issue_ym.
     *
     * @param string $issue_ym
     *
     * @return InvoiceIssueAddItem
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
     * Set item_title.
     *
     * @param string $item_title
     *
     * @return InvoiceIssueAddItem
     */
    public function setItemTitle($item_title)
    {
        $this->item_title = $item_title;

        return $this;
    }

    /**
     * Get item_title.
     *
     * @return string
     */
    public function getItemTitle()
    {
        return $this->item_title;
    }


    /**
     * Set item_amout.
     *
     * @param integer $item_amout
     *
     * @return InvoiceIssueAddItem
     */
    public function setItemAmout($item_amout)
    {
        $this->item_amout = $item_amout;

        return $this;
    }

    /**
     * Get item_amout.
     *
     * @return integer
     */
    public function getItemAmout()
    {
        return $this->item_amout;
    }

    /**
     * Set item_remark.
     *
     * @param string $item_remark
     *
     * @return InvoiceIssueAddItem
     */
    public function setItemRemark($item_remark)
    {
        $this->item_remark = $item_remark;

        return $this;
    }

    /**
     * Get item_remark.
     *
     * @return string
     */
    public function getItemRemark()
    {
        return $this->item_remark;
    }

    /**
     * Set pdf_file.
     *
     * @param string $pdf_file
     *
     * @return InvoiceIssueAddItem
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
     * Set message.
     *
     * @param string $message
     *
     * @return InvoiceIssueAddItem
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set remark.
     *
     * @param string $remark
     *
     * @return InvoiceIssueAddItem
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set revision.
     *
     * @param integer $revision
     *
     * @return InvoiceIssueAddItem
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
     * @return InvoiceIssueAddItem
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
     * @return InvoiceIssueAddItem
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
