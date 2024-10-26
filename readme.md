# Prohirify

## Table of Contents
- [Introduction](#introduction)
- [Configuration](#configuration)
- [API Endpoints](#api-endpoints)
  - [Upload Job Description](#upload-job-description)
  - [Upload Resume against JD](#upload-resume-against-jd)
  - [Get Fitment Results for JD](#get-fitment-results-for-jd)
- [Sample Code](#sample-code)

## Introduction

Welcome to the Prohirify! Our platform provides a set of APIs for analyzing resumes against job descriptions. Our APIs are designed to be easy to use and integrate into your system, regardless of the programming language you use.

You can visit our website for more information: [https://prohirify.researchify.io](https://prohirify.researchify.io)


## Configuration

To get started with the Prohirify API, you will need to:

- Sign up for an account on our website
- Obtain an API key from the Prohirify by contacting our support team. [Contact Us](https://prohirify.researchify.io/contactus)
- Choose the API endpoint you want to use
- Use the API key to authenticate your requests

## API Endpoints

### Upload Job Description

Upload a job description file to our server

- [POST] `https://prohirify-api.researchify.io/prohirify/v1/docs/jd`

### Upload Resume against JD

Upload a resume file against a previously uploaded job description

- [POST] `https://prohirify-api.researchify.io/prohirify/v1/docs/resume?jd_id={jd_id}`

### Get Fitment Results for JD

Retrieve the fitment results for a previously uploaded job description and resume

- [POST] `https://prohirify-api.researchify.io/prohirify/v1/docs/jd/{jd_id}/fitment`

## Sample Code

Here are some sample code snippets you can refer to get started:

- [python](./python/sample.py)
- [php](./php/sample.php)


