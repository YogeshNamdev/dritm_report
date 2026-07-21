<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['ai_agent_provider'] = 'huggingface_api';

$config['ai_agent_huggingface_api_url'] = 'https://yogesh10101998-ai-agent-remark-enhance.hf.space/api/enhance';
$config['ai_agent_huggingface_token'] = getenv('HUGGINGFACE_API_TOKEN') ? getenv('HUGGINGFACE_API_TOKEN') : '';

$config['ai_agent_allow_direct_groq_fallback'] = TRUE;
$config['ai_agent_groq_api_url'] = 'https://api.groq.com/openai/v1/chat/completions';
$config['ai_agent_groq_model'] = 'llama-3.1-8b-instant';
$config['ai_agent_groq_api_key'] = getenv('GROQ_API_KEY') ? getenv('GROQ_API_KEY') : '';

$config['ai_agent_timeout'] = 60;
$config['ai_agent_connect_timeout'] = 30;
$config['ai_agent_ssl_verify'] = TRUE;
$config['ai_agent_ca_bundle'] = '';
$config['ai_agent_allowed_origins'] = array();
