"""
Following is a sample code to demonstrate how to use Prohirify API to analyze resumes against a job description.
"""

# pip install requests
import os
from pprint import pprint
from time import sleep

import requests

# Configuration
API_BASE_URL = "https://prohirify-api.researchify.io/prohirify/v1"
API_KEY = 'YOUR_API_KEY_HERE'


# Utility function to set headers
def get_headers():
    return {
        "x-api-key": API_KEY,
    }


# 1. Upload Job Description
def upload_job_description(jd_file_path):
    url = f"{API_BASE_URL}/docs/jd"
    headers = get_headers()
    files = {'file': open(jd_file_path, 'rb')}

    response = requests.post(url, headers=headers, files=files)

    if response.status_code == 200:
        jd_data = response.json()
        jd_id = jd_data.get("doc_id")
        print(f"JD uploaded successfully. JD ID: {jd_id}")
        return jd_id
    else:
        print(f"Failed to upload JD. Error: {response.text}")
        return None


# 2. Upload Resume against JD
def upload_resume(jd_id, resume_file_path):
    url = f"{API_BASE_URL}/docs/resume?jd_id={jd_id}"
    headers = get_headers()
    files = {'file': open(resume_file_path, 'rb')}

    response = requests.post(url, headers=headers, files=files)

    if response.status_code == 200:
        resume_data = response.json()
        resume_id = resume_data.get("doc_id")
        print(f"Resume uploaded successfully. Resume ID: {resume_id}")
        return resume_id
    else:
        print(f"Failed to upload resume. Error: {response.text}")
        return None


# 3. Get Fitment Results for JD
def get_fitment_results(jd_id):
    url = f"{API_BASE_URL}/docs/jd/{jd_id}/fitment"
    headers = get_headers()

    response = requests.get(url, headers=headers)

    if response.status_code == 200:
        fitment_data = response.json()
        return fitment_data
    else:
        print(f"Failed to fetch fitment results. Error: {response.text}")
        return None


# Main execution function
def main():
    jd_file_path = os.path.join(os.path.dirname(__file__), 'Researchify Labs - Fullstack Engineer.pdf')
    resume_file_path = os.path.join(os.path.dirname(__file__), 'Resume - Flint Doe.pdf')

    # Step 1: Upload JD
    print('Step 1: Uploading Job Description.. ')
    jd_id = upload_job_description(jd_file_path)

    if jd_id:
        # Step 2: Upload Resume
        print('Step 2: Uploading Resume.. ')
        resume_id = upload_resume(jd_id, resume_file_path)

        if resume_id:
            # Step 3: Get Fitment Results
            while True:
                print('Step 3: Fetching Fitment Results.. ')
                fitment_results = get_fitment_results(jd_id)
                if fitment_results:
                    status = [x['status'] for x in fitment_results['resumes']][0]
                    print(f"Fitment Results: {status}")

                    if status == 'analyzed':
                        print(f'Fitment results fetched successfully.')
                        pprint(fitment_results)
                        break
                    elif status.endswith('error'):
                        print(f'Error occurred while fetching fitment results. {status}')
                        break
                sleep(5)


if __name__ == "__main__":
    main()
