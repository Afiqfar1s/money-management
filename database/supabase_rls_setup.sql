-- ============================================================
-- Supabase Row Level Security (RLS) Setup for Money Management
-- ============================================================
-- Run this SQL in Supabase Dashboard → SQL Editor
-- 
-- IMPORTANT: Run this AFTER your Laravel migrations have created
-- all the tables (companies, company_user, debtors, payments, etc.)
-- ============================================================

-- Step 1: Create helper functions for RLS policies
-- These functions read session variables set by Laravel middleware

-- Get current app user ID
CREATE OR REPLACE FUNCTION current_app_user_id()
RETURNS BIGINT AS $$
BEGIN
  RETURN NULLIF(current_setting('app.current_user_id', true), '')::BIGINT;
EXCEPTION
  WHEN OTHERS THEN RETURN NULL;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Check if current user is admin
CREATE OR REPLACE FUNCTION is_app_admin()
RETURNS BOOLEAN AS $$
BEGIN
  RETURN COALESCE(current_setting('app.is_admin', true), 'false')::BOOLEAN;
EXCEPTION
  WHEN OTHERS THEN RETURN FALSE;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Get current company ID from session
CREATE OR REPLACE FUNCTION current_company_id()
RETURNS BIGINT AS $$
BEGIN
  RETURN NULLIF(current_setting('app.current_company_id', true), '')::BIGINT;
EXCEPTION
  WHEN OTHERS THEN RETURN NULL;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;


-- Step 2: Enable RLS on all tenant-scoped tables
ALTER TABLE companies ENABLE ROW LEVEL SECURITY;
ALTER TABLE company_user ENABLE ROW LEVEL SECURITY;
ALTER TABLE debtors ENABLE ROW LEVEL SECURITY;
ALTER TABLE payments ENABLE ROW LEVEL SECURITY;

-- Enable RLS for balance_adjustments if it exists
DO $$
BEGIN
  IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'balance_adjustments') THEN
    EXECUTE 'ALTER TABLE balance_adjustments ENABLE ROW LEVEL SECURITY';
  END IF;
END $$;

-- FORCE RLS even for table owners (important for service role connections)
ALTER TABLE companies FORCE ROW LEVEL SECURITY;
ALTER TABLE company_user FORCE ROW LEVEL SECURITY;
ALTER TABLE debtors FORCE ROW LEVEL SECURITY;
ALTER TABLE payments FORCE ROW LEVEL SECURITY;

DO $$
BEGIN
  IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'balance_adjustments') THEN
    EXECUTE 'ALTER TABLE balance_adjustments FORCE ROW LEVEL SECURITY';
  END IF;
END $$;


-- Step 3: Create RLS Policies

-- ========== COMPANIES ==========
-- Drop existing policies if any
DROP POLICY IF EXISTS "companies_select" ON companies;
DROP POLICY IF EXISTS "companies_insert" ON companies;
DROP POLICY IF EXISTS "companies_update" ON companies;
DROP POLICY IF EXISTS "companies_delete" ON companies;

-- Admins can see all companies, users see only their assigned companies
CREATE POLICY "companies_select" ON companies FOR SELECT USING (
  is_app_admin() 
  OR id IN (
    SELECT company_id FROM company_user WHERE user_id = current_app_user_id()
  )
);

-- Only admins can create companies
CREATE POLICY "companies_insert" ON companies FOR INSERT WITH CHECK (
  is_app_admin()
);

-- Only admins can update companies
CREATE POLICY "companies_update" ON companies FOR UPDATE USING (
  is_app_admin()
);

-- Only admins can delete companies
CREATE POLICY "companies_delete" ON companies FOR DELETE USING (
  is_app_admin()
);


-- ========== COMPANY_USER ==========
DROP POLICY IF EXISTS "company_user_select" ON company_user;
DROP POLICY IF EXISTS "company_user_insert" ON company_user;
DROP POLICY IF EXISTS "company_user_update" ON company_user;
DROP POLICY IF EXISTS "company_user_delete" ON company_user;

-- Users can see their own company assignments, admins see all
CREATE POLICY "company_user_select" ON company_user FOR SELECT USING (
  is_app_admin() OR user_id = current_app_user_id()
);

-- Only admins can manage company assignments
CREATE POLICY "company_user_insert" ON company_user FOR INSERT WITH CHECK (
  is_app_admin()
);

CREATE POLICY "company_user_update" ON company_user FOR UPDATE USING (
  is_app_admin()
);

CREATE POLICY "company_user_delete" ON company_user FOR DELETE USING (
  is_app_admin()
);


-- ========== DEBTORS ==========
DROP POLICY IF EXISTS "debtors_select" ON debtors;
DROP POLICY IF EXISTS "debtors_insert" ON debtors;
DROP POLICY IF EXISTS "debtors_update" ON debtors;
DROP POLICY IF EXISTS "debtors_delete" ON debtors;

-- Users can only see debtors in their current company
CREATE POLICY "debtors_select" ON debtors FOR SELECT USING (
  is_app_admin() 
  OR company_id = current_company_id()
);

-- Users can create debtors only in their current company
CREATE POLICY "debtors_insert" ON debtors FOR INSERT WITH CHECK (
  is_app_admin() 
  OR company_id = current_company_id()
);

-- Users can update debtors only in their current company
CREATE POLICY "debtors_update" ON debtors FOR UPDATE USING (
  is_app_admin() 
  OR company_id = current_company_id()
);

-- Users can delete debtors only in their current company
CREATE POLICY "debtors_delete" ON debtors FOR DELETE USING (
  is_app_admin() 
  OR company_id = current_company_id()
);


-- ========== PAYMENTS ==========
DROP POLICY IF EXISTS "payments_select" ON payments;
DROP POLICY IF EXISTS "payments_insert" ON payments;
DROP POLICY IF EXISTS "payments_update" ON payments;
DROP POLICY IF EXISTS "payments_delete" ON payments;

-- Users can only access payments for debtors in their current company
CREATE POLICY "payments_select" ON payments FOR SELECT USING (
  is_app_admin() 
  OR debtor_id IN (
    SELECT id FROM debtors WHERE company_id = current_company_id()
  )
);

CREATE POLICY "payments_insert" ON payments FOR INSERT WITH CHECK (
  is_app_admin() 
  OR debtor_id IN (
    SELECT id FROM debtors WHERE company_id = current_company_id()
  )
);

CREATE POLICY "payments_update" ON payments FOR UPDATE USING (
  is_app_admin() 
  OR debtor_id IN (
    SELECT id FROM debtors WHERE company_id = current_company_id()
  )
);

CREATE POLICY "payments_delete" ON payments FOR DELETE USING (
  is_app_admin() 
  OR debtor_id IN (
    SELECT id FROM debtors WHERE company_id = current_company_id()
  )
);


-- ========== BALANCE_ADJUSTMENTS (if exists) ==========
DO $$
BEGIN
  IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'balance_adjustments') THEN
    -- Drop existing policies
    EXECUTE 'DROP POLICY IF EXISTS "balance_adjustments_select" ON balance_adjustments';
    EXECUTE 'DROP POLICY IF EXISTS "balance_adjustments_insert" ON balance_adjustments';
    EXECUTE 'DROP POLICY IF EXISTS "balance_adjustments_update" ON balance_adjustments';
    EXECUTE 'DROP POLICY IF EXISTS "balance_adjustments_delete" ON balance_adjustments';
    
    -- Create policies (assuming balance_adjustments references debtor_id)
    EXECUTE '
      CREATE POLICY "balance_adjustments_select" ON balance_adjustments FOR SELECT USING (
        is_app_admin() 
        OR debtor_id IN (
          SELECT id FROM debtors WHERE company_id = current_company_id()
        )
      )
    ';
    
    EXECUTE '
      CREATE POLICY "balance_adjustments_insert" ON balance_adjustments FOR INSERT WITH CHECK (
        is_app_admin() 
        OR debtor_id IN (
          SELECT id FROM debtors WHERE company_id = current_company_id()
        )
      )
    ';
    
    EXECUTE '
      CREATE POLICY "balance_adjustments_update" ON balance_adjustments FOR UPDATE USING (
        is_app_admin() 
        OR debtor_id IN (
          SELECT id FROM debtors WHERE company_id = current_company_id()
        )
      )
    ';
    
    EXECUTE '
      CREATE POLICY "balance_adjustments_delete" ON balance_adjustments FOR DELETE USING (
        is_app_admin() 
        OR debtor_id IN (
          SELECT id FROM debtors WHERE company_id = current_company_id()
        )
      )
    ';
  END IF;
END $$;


-- ============================================================
-- VERIFICATION: Check that RLS is enabled
-- ============================================================
SELECT 
  schemaname,
  tablename,
  rowsecurity
FROM pg_tables 
WHERE schemaname = 'public' 
  AND tablename IN ('companies', 'company_user', 'debtors', 'payments', 'balance_adjustments');

-- List all policies
SELECT 
  schemaname,
  tablename,
  policyname,
  permissive,
  roles,
  cmd,
  qual
FROM pg_policies 
WHERE schemaname = 'public'
ORDER BY tablename, policyname;
