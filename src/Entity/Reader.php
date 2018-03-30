<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReaderRepository")
 */
class Reader
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="fullname", type="string")
     */
    private $fullname;

    /**
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @ORM\Column(name="street", type="string")
     */
    private $street;

    /**
     * @ORM\Column(name="zip_codee", type="string")
     */
    private $zip_code;

    /**
     * @ORM\Column(name="adress", type="string")
     */
    private $adress;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Unit", mappedBy="reader")
     * @ORM\JoinColumn(name="reader_id", referencedColumnName="id")
     */
    private $unit;

    public function __construct()
    {
        $this->unit = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname): void
    {
        $this->fullname = $fullname;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * @param mixed $zip_code
     */
    public function setZipCode($zip_code): void
    {
        $this->zip_code = $zip_code;
    }

    /**
     * @return mixed
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * @param mixed $adress
     */
    public function setAdress($adress): void
    {
        $this->adress = $adress;
    }

    /**
     * @return ArrayCollection
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     * @return $this
     */
    public function addUnit(Unit $unit)
    {
        $this->unit[] = $unit;
        return $this;
    }

    /**
     * @param Unit $unit
     * @return null
     */
    public function removeUnit(Unit $unit)
    {
        $this->unit->removeElement($unit);

        return $this->unit;
    }

}
