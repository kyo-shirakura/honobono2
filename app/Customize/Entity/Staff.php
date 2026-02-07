<?php

namespace Customize\Entity;

use Customize\Entity\Master\StaffStatus;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Staff
 *
 * @ORM\Table(name="dtb_staff")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\StaffRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Staff extends AbstractEntity
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
     * @var string
     *
     * @ORM\Column(name="name01", type="string", length=255)
     */
    private $name01;

    /**
     * @var string
     *
     * @ORM\Column(name="name02", type="string", length=255)
     */
    private $name02;

    /**
     * @var string|null
     *
     * @ORM\Column(name="kana01", type="string", length=255, nullable=true)
     */
    private $kana01;

    /**
     * @var string|null
     *
     * @ORM\Column(name="kana02", type="string", length=255, nullable=true)
     */
    private $kana02;

    /**
     * @var string|null
     *
     * @ORM\Column(name="postal_code", type="string", length=8, nullable=true)
     */
    private $postal_code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="addr01", type="string", length=255, nullable=true)
     */
    private $addr01;

    /**
     * @var string|null
     *
     * @ORM\Column(name="addr02", type="string", length=255, nullable=true)
     */
    private $addr02;

    /**
     * @var string|null
     *
     * @ORM\Column(name="access", type="string", length=255, nullable=true)
     */
    private $access;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=14, nullable=true)
     */
    private $phone_number;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="birth", type="datetimetz", nullable=true)
     */
    private $birth;

    /**
     * @var string
     *
     * @ORM\Column(name="transferee_account", type="string", length=255, nullable=true)
     */
    private $transferee_account;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note", type="string", length=4000, nullable=true)
     */
    private $note;

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
     * @var \Customize\Entity\Master\StaffStatus
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\StaffStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stuff_status_id", referencedColumnName="id")
     * })
     */
    private $Status;

    /**
     * @var \Eccube\Entity\Master\Sex
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Master\Sex")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sex_id", referencedColumnName="id")
     * })
     */
    private $Sex;

    /**
     * @var \Eccube\Entity\Master\Pref
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Master\Pref")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pref_id", referencedColumnName="id")
     * })
     */
    private $Pref;

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
        return (string) ($this->getName01().' '.$this->getName02());
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /*
    // TODO: できればFormTypeで行いたい
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'email',
            'message' => 'form_error.customer_already_exists',
            'repositoryMethod' => 'getNonWithdrawingCustomers',
        ]));
    }
    */

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
     * Set name01.
     *
     * @param string $name01
     *
     * @return Staff
     */
    public function setName01($name01)
    {
        $this->name01 = $name01;

        return $this;
    }

    /**
     * Get name01.
     *
     * @return string
     */
    public function getName01()
    {
        return $this->name01;
    }

    /**
     * Set name02.
     *
     * @param string $name02
     *
     * @return Staff
     */
    public function setName02($name02)
    {
        $this->name02 = $name02;

        return $this;
    }

    /**
     * Get name02.
     *
     * @return string
     */
    public function getName02()
    {
        return $this->name02;
    }

    /**
     * Set access.
     *
     * @param string $access
     *
     * @return Staff
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access.
     *
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set kana01.
     *
     * @param string|null $kana01
     *
     * @return Staff
     */
    public function setKana01($kana01 = null)
    {
        $this->kana01 = $kana01;

        return $this;
    }

    /**
     * Get kana01.
     *
     * @return string|null
     */
    public function getKana01()
    {
        return $this->kana01;
    }

    /**
     * Set kana02.
     *
     * @param string|null $kana02
     *
     * @return Staff
     */
    public function setKana02($kana02 = null)
    {
        $this->kana02 = $kana02;

        return $this;
    }

    /**
     * Get kana02.
     *
     * @return string|null
     */
    public function getKana02()
    {
        return $this->kana02;
    }

    /**
     * Set companyName.
     *
     * @param string|null $companyName
     *
     * @return Staff
     */
    public function setCompanyName($companyName = null)
    {
        $this->company_name = $companyName;

        return $this;
    }

    /**
     * Get companyName.
     *
     * @return string|null
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * Set postal_code.
     *
     * @param string|null $postal_code
     *
     * @return Staff
     */
    public function setPostalCode($postal_code = null)
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    /**
     * Get postal_code.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set addr01.
     *
     * @param string|null $addr01
     *
     * @return Staff
     */
    public function setAddr01($addr01 = null)
    {
        $this->addr01 = $addr01;

        return $this;
    }

    /**
     * Get addr01.
     *
     * @return string|null
     */
    public function getAddr01()
    {
        return $this->addr01;
    }

    /**
     * Set addr02.
     *
     * @param string|null $addr02
     *
     * @return Staff
     */
    public function setAddr02($addr02 = null)
    {
        $this->addr02 = $addr02;

        return $this;
    }

    /**
     * Get addr02.
     *
     * @return string|null
     */
    public function getAddr02()
    {
        return $this->addr02;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Staff
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone_number.
     *
     * @param string|null $phone_number
     *
     * @return Staff
     */
    public function setPhoneNumber($phone_number = null)
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * Get phone_number.
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Set birth.
     *
     * @param \DateTime|null $birth
     *
     * @return Staff
     */
    public function setBirth($birth = null)
    {
        $this->birth = $birth;

        return $this;
    }

    /**
     * Get birth.
     *
     * @return \DateTime|null
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * Set transferee_account.
     *
     * @param string $transferee_account
     *
     * @return string
     */
    public function setTransfereeAccount($transferee_account)
    {
        $this->transferee_account = $transferee_account;

        return $this;
    }

    /**
     * Get transferee_account.
     *
     * @return string
     */
    public function getTransfereeAccount()
    {
        return $this->transferee_account;
    }

    /**
     * Set note.
     *
     * @param string|null $note
     *
     * @return Staff
     */
    public function setNote($note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set resetKey.
     *
     * @param string|null $resetKey
     *
     * @return Staff
     */
    public function setResetKey($resetKey = null)
    {
        $this->reset_key = $resetKey;

        return $this;
    }

    /**
     * Get resetKey.
     *
     * @return string|null
     */
    public function getResetKey()
    {
        return $this->reset_key;
    }

    /**
     * Set resetExpire.
     *
     * @param \DateTime|null $resetExpire
     *
     * @return Staff
     */
    public function setResetExpire($resetExpire = null)
    {
        $this->reset_expire = $resetExpire;

        return $this;
    }

    /**
     * Get resetExpire.
     *
     * @return \DateTime|null
     */
    public function getResetExpire()
    {
        return $this->reset_expire;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return Staff
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
     * @return Staff
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
     * Set status.
     *
     * @param \Customize\Entity\Master\StaffStatus|null $status
     *
     * @return Staff
     */
    public function setStatus(\Customize\Entity\Master\StaffStatus $status = null)
    {
        $this->Status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return \Customize\Entity\Master\StaffStatus|null
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * Set sex.
     *
     * @param \Eccube\Entity\Master\Sex|null $sex
     *
     * @return Staff
     */
    public function setSex(\Eccube\Entity\Master\Sex $sex = null)
    {
        $this->Sex = $sex;

        return $this;
    }

    /**
     * Get sex.
     *
     * @return \Eccube\Entity\Master\Sex|null
     */
    public function getSex()
    {
        return $this->Sex;
    }

    /**
     * Set pref.
     *
     * @param \Eccube\Entity\Master\Pref|null $pref
     *
     * @return Staff
     */
    public function setPref(\Eccube\Entity\Master\Pref $pref = null)
    {
        $this->Pref = $pref;

        return $this;
    }

    /**
     * Get pref.
     *
     * @return \Eccube\Entity\Master\Pref|null
     */
    public function getPref()
    {
        return $this->Pref;
    }

    /**
     * String representation of object
     *
     * @see http://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     *
     * @since 5.1.0
     */
    public function serialize()
    {
        // see https://symfony.com/doc/2.7/security/entity_provider.html#create-your-user-entity
        // CustomerRepository::loadUserByUsername() で Status をチェックしているため、ここでは不要
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
        ]);
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
